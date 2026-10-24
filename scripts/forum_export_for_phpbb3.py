#!/usr/bin/env python3
"""Export forum_private.sqlite to import.json for repo/forums/import/import_archive.php.

Usage: python3 -I forum_export_for_phpbb3.py <forum_private.sqlite> <out.json>

Converts phpBB2 BBCode (uid suffixes, entity escaping) and IPB 2.x HTML to plain phpBB3
BBCode, merges members across eras by username, and writes archive-only stubs for authors
without a member row. PMs are deliberately NOT exported. The output holds emails: keep it
outside git (mode 600) and delete it after the import.
"""
import html, json, os, re, sqlite3, sys

SRC, OUT = sys.argv[1], sys.argv[2]
db = sqlite3.connect('file:' + SRC + '?mode=ro', uri=True)

EMO = {'biggrin': ':D', 'tongue': ':P', 'laugh': ':lol:', 'mad': ':x', 'sad': ':(', 'smile': ':)', 'dry': ':roll:', 'wink': ';)',
       'huh': ':?', 'blink': ':shock:', 'cool': '8-)', 'ohmy': ':o', 'sleep': ':|', 'rolleyes': ':roll:', 'mellow': ':|',
       'ph34r': ':twisted:', 'happy': ':D', 'unsure': ':?', 'wacko': ':shock:'}
SIZE = {'1': 50, '2': 85, '3': 100, '4': 120, '5': 150, '6': 175, '7': 200}


def phpbb_text(s):
    s = re.sub(r":(?:[a-z]:)?[0-9a-f]{10}(?=[\]=])", "", s or "")          # strip per-post bbcode_uid
    s = re.sub(r"\[/?(?:fg|fingerquote|username|apps)\]", "", s, flags=re.I)  # custom 2005 tags with no phpBB3 equivalent
    return html.unescape(s)


def ipb_text(s):
    s = s or ""
    s = re.sub(r"\[snapback\]\d+\[/snapback\]", "", s)
    s = s.replace("<br />", "\n").replace("<br>", "\n")
    s = re.sub(r"<!--coloro:(#?\w+)--><span[^>]*><!--/coloro-->", r"[color=\1]", s)
    s = re.sub(r"<!--colorc--></span><!--/colorc-->", "[/color]", s)
    s = re.sub(r"<!--sizeo:(\d)--><span[^>]*><!--/sizeo-->", lambda m: f"[size={SIZE.get(m.group(1), 100)}]", s)
    s = re.sub(r"<!--sizec--></span><!--/sizec-->", "[/size]", s)
    s = re.sub(r"<!--fonto:[^>]*?--><span[^>]*><!--/fonto-->|<!--fontc--></span><!--/fontc-->", "", s)
    # quote/code boxes are div pairs: walk the divs with a stack
    out, stack, pos = [], [], 0
    for m in re.finditer(r"<div class=['\"](quotetop|quotemain|codetop|codemain)['\"]>|<div[^>]*>|</div>", s):
        if m.start() < pos:
            continue  # inside a header we already consumed
        out.append(s[pos:m.start()]); pos = m.end()
        kind = m.group(1)
        if kind in ('quotetop', 'codetop'):
            end = s.index("</div>", pos)
            head, pos = s[pos:end], end + len("</div>")
            if kind == 'quotetop':
                q = html.unescape(re.sub(r"<[^>]+>|\[snapback\].*?\[/snapback\]", "", head)).strip()
                n = re.match(r"QUOTE\((.*?)(?: @ .*)?\)$", q)
                out.append('[quote="%s"]' % n.group(1).strip('"') if n and n.group(1) else '[quote]')
            continue
        if kind == 'quotemain':
            stack.append('quote'); continue
        if kind == 'codemain':
            stack.append('code'); out.append('[code]'); continue
        if m.group(0) == '</div>':
            k = stack.pop() if stack else None
            if k: out.append('[/%s]' % k)
            continue
        stack.append(None)  # plain <div>
    out.append(s[pos:]); s = "".join(out)
    s = re.sub(r"<img src=[\"']style_emoticons/<#EMO_DIR#>/(\w+)\.gif[\"'][^>]*>", lambda m: " " + EMO.get(m.group(1), ":%s:" % m.group(1)) + " ", s)
    s = re.sub(r"<a href=[\"']mailto:([^\"']+)[\"'][^>]*>.*?</a>", r"[email]\1[/email]", s, flags=re.S)
    s = re.sub(r"<a href=[\"']([^\"']+)[\"'][^>]*>(.*?)</a>", r"[url=\1]\2[/url]", s, flags=re.S)
    s = re.sub(r"<img src=[\"']([^\"']+)[\"'][^>]*>", r"[img]\1[/img]", s)
    for a, b in (("b", "b"), ("i", "i"), ("u", "u")):
        s = re.sub(r"<%s>" % a, "[%s]" % b, s); s = re.sub(r"</%s>" % a, "[/%s]" % b, s)
    s = re.sub(r"<ol type=['\"]?(\w)['\"]?>", r"[list=\1]", s)
    s = s.replace("<ul>", "[list]").replace("</ul>", "[/list]").replace("</ol>", "[/list]")
    s = s.replace("<li>", "[*]").replace("</li>", "")
    s = re.sub(r"<!--.*?-->", "", s, flags=re.S)
    s = re.sub(r"<[^>]+>", "", s)
    s = re.sub(r"\[/?(?:strike|s)\]", "", s)
    return html.unescape(s)


conv = {'phpbb': phpbb_text, 'ipb': ipb_text}
h = lambda t: html.unescape(t or "")

eras = [('phpbb', '2005 board (phpBB)'), ('ipb', '2006 board (Invision)')]
cats = [{'name': name, 'src': src, 'forums': [{'id': i, 'name': h(n)} for i, n in db.execute("select id,name from forums where src=? order by id", (src,))]}
        for src, name in eras]

# accounts: one per username (case-insensitive); later era wins for email/profile
acc = {}
for row in db.execute("select src,id,username,email,joined,posts,title,location,website,signature,bio,interests,occupation,aim,icq,msn,yahoo "
                      "from members order by case src when 'phpbb' then 0 else 1 end, id"):
    src, _id, uname, email, joined, posts, title, loc, web, sig, bio, interests, occ, aim, icq, msn, yahoo = row
    k = uname.lower()
    a = acc.setdefault(k, {'username': h(uname), 'eras': []})
    a['eras'].append(src)
    a['email'] = email or a.get('email')
    a['joined'] = min(int(joined or 0), a.get('joined') or 10**12) or None
    a['sig'] = conv[src](sig) if sig else a.get('sig')
    prof = {'location': loc, 'website': web, 'interests': interests or bio, 'occupation': occ, 'icq': icq, 'aim': aim, 'yahoo': yahoo, 'msn': msn}
    a['profile'] = {**a.get('profile', {}), **{pk: html.unescape(pv) for pk, pv in prof.items() if pv and pv != '0'}}

authors = {(s, a) for s, a in db.execute("select distinct src,author from posts")}
stubs = []
for _, a in sorted(authors):
    if a.lower() in acc or a.lower() == 'anonymous' or a.lower() in {x['username'].lower() for x in stubs}:
        continue
    stubs.append({'username': h(a)})

pk = lambda src, i: f"{src}:{i}"
topics = [{'key': pk(s, i), 'forum': pk(s, f), 'title': h(t), 'starter': st, 'ts': int(ts or 0)}
          for s, i, f, t, st, ts in db.execute("select src,id,forum_id,title,starter,ts from topics")]
tkey = {t['key']: t for t in topics}
posts, dropped = [], 0
for s, i, t, f, author, ts, subj, body in db.execute("select src,id,topic_id,forum_id,author,ts,subject,body from posts order by ts,id"):
    if re.search(r"eval\(\s*chr\(", body or ""):  # remote-file-include attack post (see docs/forums/spam-scan)
        dropped += 1; continue
    if pk(s, t) not in tkey:
        dropped += 1; continue
    posts.append({'key': pk(s, i), 'topic': pk(s, t), 'author': h(author), 'ts': int(ts or 0), 'subject': '' if subj in (None, 'NULL') else h(subj), 'text': conv[s](body).strip()})

json.dump({'categories': cats, 'accounts': list(acc.values()), 'stubs': stubs, 'topics': topics, 'posts': posts}, open(OUT, 'w'))
os.chmod(OUT, 0o600)
print(f"{len(cats)} categories, {sum(len(c['forums']) for c in cats)} forums, {len(acc)} accounts "
      f"({sum(1 for a in acc.values() if len(a['eras']) > 1)} spanning both eras), {len(stubs)} archive-only stubs, "
      f"{len(topics)} topics, {len(posts)} posts ({dropped} dropped), PMs not exported")
