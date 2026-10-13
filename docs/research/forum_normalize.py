"""Merge the 2005 phpBB2 and 2005-06 IPB forum dumps into one SQLite file.

Usage: python3 -I forum_normalize.py <backups dir> <out.sqlite>

Keeps forums, topics, posts and member names. Deliberately drops private
messages, IP addresses, emails and password hashes. Keep the output file
outside the repo.
"""
import re, sqlite3, sys, os

B, OUT = sys.argv[1], sys.argv[2]
Q = re.compile(r"'((?:[^'\\]|\\.|'')*)'|(-?\d+(?:\.\d+)?|NULL)")
ESC = {'n': '\n', 'r': '\r', 't': '\t', '0': '\0', 'Z': '\x1a'}


def unq(s):
    return re.sub(r"\\(.)|''", lambda m: "'" if m.group(0) == "''" else ESC.get(m.group(1), m.group(1)), s)


def vals(body):
    return [unq(a) if a != '' or b == '' else b for a, b in Q.findall(body)]


def phpbb(path, tab):
    # statements can span lines (raw newlines inside posts), so match whole statements
    text = open(path, encoding='latin-1').read()
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


if os.path.exists(OUT):
    os.remove(OUT)
db = sqlite3.connect(OUT)
db.executescript("""
CREATE TABLE forums(src TEXT, id INT, name TEXT, PRIMARY KEY(src,id));
CREATE TABLE topics(src TEXT, id INT, forum_id INT, title TEXT, starter TEXT, ts INT, PRIMARY KEY(src,id));
CREATE TABLE posts(src TEXT, id INT, topic_id INT, forum_id INT, author TEXT, ts INT, subject TEXT, body TEXT, PRIMARY KEY(src,id));
""")
# phpBB2: December 2005 is the cumulative snapshot
p = B + '/phpBB Database/December, 2005/phpbb_db_backup.sql'
users = {r['user_id']: r['username'] for r in phpbb(p, 'phpbb_users')}
text = {r['post_id']: r for r in phpbb(p, 'phpbb_posts_text')}
db.executemany("INSERT INTO forums VALUES('phpbb',?,?)", [(r['forum_id'], r['forum_name']) for r in phpbb(p, 'phpbb_forums')])
db.executemany("INSERT INTO topics VALUES('phpbb',?,?,?,?,?)", [(r['topic_id'], r['forum_id'], r['topic_title'], users.get(r['topic_poster'], 'Guest'), r['topic_time']) for r in phpbb(p, 'phpbb_topics')])
rows = []
for r in phpbb(p, 'phpbb_posts'):
    t = text.get(r['post_id'], {})
    author = users.get(r['poster_id']) or r.get('post_username') or 'Guest'
    rows.append((r['post_id'], r['topic_id'], r['forum_id'], author, r['post_time'], t.get('post_subject', ''), t.get('post_text', '')))
db.executemany("INSERT INTO posts VALUES('phpbb',?,?,?,?,?,?,?)", rows)
# IPB
t = open(B + '/old dbs/fgcomics_forums.sql', encoding='latin-1').read()
db.executemany("INSERT INTO forums VALUES('ipb',?,?)", [(r['id'], r['name']) for r in ipb(t, 'ibf_forums')])
tf = {}
for r in ipb(t, 'ibf_topics'):
    tf[r['tid']] = r['forum_id']
    db.execute("INSERT INTO topics VALUES('ipb',?,?,?,?,?)", (r['tid'], r['forum_id'], r['title'], r['starter_name'], r['start_date']))
db.executemany("INSERT INTO posts VALUES('ipb',?,?,?,?,?,?,?)", [(r['pid'], r['topic_id'], tf.get(r['topic_id']), r['author_name'], r['post_date'], r['post_title'], r['post']) for r in ipb(t, 'ibf_posts')])
db.commit()
for s in ('phpbb', 'ipb'):
    print(s, db.execute("SELECT count(*),count(distinct author),min(ts),max(ts) FROM posts WHERE src=?", (s,)).fetchone(),
          'topics', db.execute("SELECT count(*) FROM topics WHERE src=?", (s,)).fetchone()[0],
          'orphan posts', db.execute("SELECT count(*) FROM posts p WHERE src=? AND NOT EXISTS(SELECT 1 FROM topics t WHERE t.src=p.src AND t.id=p.topic_id)", (s,)).fetchone()[0],
          'empty bodies', db.execute("SELECT count(*) FROM posts WHERE src=? AND body=''", (s,)).fetchone()[0])
