#!/usr/bin/env python3
"""Build the PRIVATE forum database for account reclaim from the raw phpBB2/IPB dumps.

Usage: python3 -I forum_reclaim_build.py <backups dir> <out.sqlite>

Keeps: forums, topics, posts, member usernames, emails, join dates, post counts, profile
fields (bio, location, website, signature, avatar, IM names) and private messages.
Drops: password hashes/salts, login/session/activation keys, every IP address, browser
strings, birthdays, and all log tables. The output contains emails and PMs: it must stay
outside git and outside any public build. A leak check runs at the end and fails the build.
"""
import os, re, sqlite3, sys

B, OUT = sys.argv[1], sys.argv[2]
Q = re.compile(r"'((?:[^'\\]|\\.|'')*)'|(-?\d+(?:\.\d+)?|NULL)")
ESC = {'n': '\n', 'r': '\r', 't': '\t', '0': '\0', 'Z': '\x1a'}


def unq(s):
    return re.sub(r"\\(.)|''", lambda m: "'" if m.group(0) == "''" else ESC.get(m.group(1), m.group(1)), s)


def vals(body):
    return [unq(a) if a != '' or b == '' else b for a, b in Q.findall(body)]


def phpbb(text, tab):
    pat = re.compile(r"^INSERT INTO " + tab + r" \(([^)]*)\) VALUES ?\((.*?)\);\n(?=INSERT INTO |#|\n|DROP |CREATE |\Z)", re.S | re.M)
    for m in pat.finditer(text):
        c = [x.strip() for x in m.group(1).split(',')]
        v = vals(m.group(2))
        if len(v) >= len(c):
            yield dict(zip(c, v))


def ipb(text, tab):
    m = re.search(r"CREATE TABLE `?" + tab + r"`? \((.*?)\n\) ", text, re.S)
    c = [re.match(r"\s*`(\w+)`", l).group(1) for l in m.group(1).split('\n') if re.match(r"\s*`\w+`", l)]
    for m in re.finditer(r"INSERT INTO `?" + tab + r"`? VALUES ?\((.*?)\);\n(?=INSERT INTO |--|\n|\Z)", text, re.S):
        v = vals(m.group(1))
        if len(v) == len(c):
            yield dict(zip(c, v))


def nz(v):
    return None if v in (None, '', 'NULL') else v


if os.path.exists(OUT):
    os.remove(OUT)
db = sqlite3.connect(OUT)
db.executescript("""
CREATE TABLE forums(src TEXT, id INT, name TEXT, PRIMARY KEY(src,id));
CREATE TABLE topics(src TEXT, id INT, forum_id INT, title TEXT, starter TEXT, ts INT, PRIMARY KEY(src,id));
CREATE TABLE posts(src TEXT, id INT, topic_id INT, forum_id INT, author TEXT, ts INT, subject TEXT, body TEXT, PRIMARY KEY(src,id));
CREATE TABLE members(src TEXT, id INT, username TEXT, email TEXT, joined INT, posts INT, title TEXT,
  location TEXT, website TEXT, signature TEXT, bio TEXT, interests TEXT, occupation TEXT,
  aim TEXT, icq TEXT, msn TEXT, yahoo TEXT, avatar TEXT, PRIMARY KEY(src,id));
CREATE TABLE pms(src TEXT, id INT, ts INT, subject TEXT, from_id INT, from_name TEXT, to_id INT, to_name TEXT, body TEXT, PRIMARY KEY(src,id));
""")

# ---- phpBB2: December 2005 is the cumulative snapshot
p = open(B + '/phpBB Database/December, 2005/phpbb_db_backup.sql', encoding='latin-1').read()
users = {r['user_id']: r for r in phpbb(p, 'phpbb_users')}
name = lambda uid: users.get(uid, {}).get('username')
text = {r['post_id']: r for r in phpbb(p, 'phpbb_posts_text')}
db.executemany("INSERT INTO forums VALUES('phpbb',?,?)", [(r['forum_id'], r['forum_name']) for r in phpbb(p, 'phpbb_forums')])
db.executemany("INSERT INTO topics VALUES('phpbb',?,?,?,?,?)",
               [(r['topic_id'], r['forum_id'], r['topic_title'], name(r['topic_poster']) or 'Guest', r['topic_time']) for r in phpbb(p, 'phpbb_topics')])
rows = []
for r in phpbb(p, 'phpbb_posts'):
    t = text.get(r['post_id'], {})
    rows.append((r['post_id'], r['topic_id'], r['forum_id'], name(r['poster_id']) or r.get('post_username') or 'Guest',
                 r['post_time'], t.get('post_subject', ''), t.get('post_text', '')))
db.executemany("INSERT INTO posts VALUES('phpbb',?,?,?,?,?,?,?)", rows)
for uid, u in users.items():
    if uid == '-1':  # phpBB's built-in Anonymous
        continue
    db.execute("INSERT INTO members VALUES('phpbb',?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
               (uid, u['username'], nz(u.get('user_email')), u.get('user_regdate'), u.get('user_posts'), nz(u.get('user_custom_title')),
                nz(u.get('user_from')), nz(u.get('user_website')), nz(u.get('user_sig')), None, nz(u.get('user_interests')), nz(u.get('user_occ')),
                nz(u.get('user_aim')), nz(u.get('user_icq')), nz(u.get('user_msnm')), nz(u.get('user_yim')), nz(u.get('user_avatar'))))
ptext = {r['privmsgs_text_id']: r.get('privmsgs_text', '') for r in phpbb(p, 'phpbb_privmsgs_text')}
db.executemany("INSERT INTO pms VALUES('phpbb',?,?,?,?,?,?,?,?)",
               [(r['privmsgs_id'], r['privmsgs_date'], r['privmsgs_subject'], r['privmsgs_from_userid'], name(r['privmsgs_from_userid']),
                 r['privmsgs_to_userid'], name(r['privmsgs_to_userid']), ptext.get(r['privmsgs_id'], '')) for r in phpbb(p, 'phpbb_privmsgs')])

# ---- IPB 2.x
t = open(B + '/old dbs/fgcomics_forums.sql', encoding='latin-1').read()
db.executemany("INSERT INTO forums VALUES('ipb',?,?)", [(r['id'], r['name']) for r in ipb(t, 'ibf_forums')])
tf = {}
for r in ipb(t, 'ibf_topics'):
    tf[r['tid']] = r['forum_id']
    db.execute("INSERT INTO topics VALUES('ipb',?,?,?,?,?)", (r['tid'], r['forum_id'], r['title'], r['starter_name'], r['start_date']))
db.executemany("INSERT INTO posts VALUES('ipb',?,?,?,?,?,?,?)",
               [(r['pid'], r['topic_id'], tf.get(r['topic_id']), r['author_name'], r['post_date'], r['post_title'], r['post']) for r in ipb(t, 'ibf_posts')])
extra = {r['id']: r for r in ipb(t, 'ibf_member_extra')}
members = list(ipb(t, 'ibf_members'))
mname = {m['id']: m['name'] for m in members}
for m in members:
    e = extra.get(m['id'], {})
    db.execute("INSERT INTO members VALUES('ipb',?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
               (m['id'], m['name'], nz(m.get('email')), m.get('joined'), m.get('posts'), nz(m.get('title')),
                nz(e.get('location')), nz(e.get('website')), nz(e.get('signature')), nz(e.get('bio')), nz(e.get('interests')), None,
                nz(e.get('aim_name')), nz(e.get('icq_number')), nz(e.get('msnname')), nz(e.get('yahoo')), nz(e.get('avatar_location'))))
mtext = {r['msg_id']: r for r in ipb(t, 'ibf_message_text')}
seen = set()
for r in ipb(t, 'ibf_message_topics'):  # one row per owner copy: keep each message once
    if r['mt_msg_id'] in seen:
        continue
    seen.add(r['mt_msg_id'])
    m = mtext.get(r['mt_msg_id'], {})
    db.execute("INSERT INTO pms VALUES('ipb',?,?,?,?,?,?,?,?)",
               (r['mt_msg_id'], r['mt_date'], r['mt_title'], r['mt_from_id'], mname.get(r['mt_from_id']), r['mt_to_id'], mname.get(r['mt_to_id']), m.get('msg_post', '')))
unlinked = len({r['converge_id'] for r in ipb(t, 'ibf_members_converge')} - set(mname))
db.commit()

# ---- leak check: no secret-looking columns, no hash-shaped values in member rows
bad = [(tb, c) for (tb,) in db.execute("select name from sqlite_master where type='table'")
       for c in [x[1] for x in db.execute(f'pragma table_info({tb})')] if re.search(r"pass|hash|salt|ip_?addr|^ip$|login_key|session|actkey", c, re.I)]
hashy = db.execute("select count(*) from members where " + " or ".join(
    f"{c} glob '[0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f][0-9a-f]'"
    for c in ("username", "email", "title", "website", "location", "signature", "bio", "interests", "occupation", "aim", "icq", "msn", "yahoo", "avatar"))).fetchone()[0]
if bad or hashy:
    os.remove(OUT)
    sys.exit(f"LEAK CHECK FAILED: columns {bad}, hash-like member values {hashy}; output deleted")
os.chmod(OUT, 0o600)
for s in ('phpbb', 'ipb'):
    print(s, 'posts', db.execute("SELECT count(*) FROM posts WHERE src=?", (s,)).fetchone()[0],
          'topics', db.execute("SELECT count(*) FROM topics WHERE src=?", (s,)).fetchone()[0],
          'members', db.execute("SELECT count(*) FROM members WHERE src=?", (s,)).fetchone()[0],
          'with email', db.execute("SELECT count(*) FROM members WHERE src=? AND email IS NOT NULL", (s,)).fetchone()[0],
          'pms', db.execute("SELECT count(*) FROM pms WHERE src=?", (s,)).fetchone()[0])
print('IPB converge/extra rows not matching any member (not imported):', unlinked)
print('leak check passed; wrote', OUT, '(mode 600; contains emails and PMs, keep out of git)')
