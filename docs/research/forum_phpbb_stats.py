import re,sys,collections,time
B=sys.argv[1]
Q=re.compile(r"'((?:[^'\\]|\\.|'')*)'|(-?\d+(?:\.\d+)?|NULL)")
def recs(p,tab):
    pat=re.compile(r"INSERT INTO `?"+tab+r"`? \(([^)]*)\) VALUES ?\((.*)\);?\s*$",re.S)
    for l in open(p,encoding='latin-1'):
        if not l.startswith('INSERT INTO'): continue
        m=pat.match(l)
        if not m: continue
        cols=[c.strip(' `') for c in m.group(1).split(',')]
        vals=[a if a!='' or b=='' else a for a,b in ((x[0] or x[1],x[1]) for x in Q.findall(m.group(2)))]
        if len(vals)>=len(cols): yield dict(zip(cols,vals))
d=lambda t:time.strftime('%Y-%m-%d',time.gmtime(int(t)))
def rng(ts): ts=[int(t) for t in ts if str(t).isdigit() and int(t)>9e8]; return (d(min(ts)),d(max(ts)),len(ts)) if ts else None
for lab,p in [('Jun05','phpBB Database/June, 2005'),('Aug05','phpBB Database/August, 2005'),('Oct05','phpBB Database/October, 2005'),('Dec05','phpBB Database/December, 2005')]:
    f=f'{B}/{p}/phpbb_db_backup.sql'; P=list(recs(f,'phpbb_posts'))
    print(lab,'posts',len(P),rng([r['post_time'] for r in P]),'users',len(list(recs(f,'phpbb_users'))))
f=f'{B}/phpBB Database/December, 2005/phpbb_db_backup.sql'
print('Dec05 forums:',[(r['forum_name'][:35],r['forum_posts']) for r in recs(f,'phpbb_forums')])
P=list(recs(f,'phpbb_posts')); print(' by month',sorted(collections.Counter(d(r['post_time'])[:7] for r in P).items()))
print(' guests:',sum(1 for r in P if r['poster_id']=='-1'),'topics',len(list(recs(f,'phpbb_topics'))),'polls',len(list(recs(f,'phpbb_vote_desc'))))
tx=list(recs(f,'phpbb_posts_text')); L=[len(r.get('post_text','')) for r in tx]; print(' text chars',sum(L),'avg',sum(L)//max(1,len(L)),'with img',sum('[img' in r.get('post_text','') for r in tx),'quotes',sum('[quote' in r.get('post_text','') for r in tx))
u=list(recs(f,'phpbb_users')); print(' users',len(u),'with avatar',sum(1 for r in u if r.get('user_avatar')),'posts>100',sum(1 for r in u if int(r.get('user_posts','0'))>100))
print(' top post counts',sorted((int(r.get('user_posts','0')) for r in u),reverse=True)[:8])
g=f'{B}/old dbs/fgcomics_forums.sql'
hdr=[l for l in open(g,encoding='latin-1') if l.startswith('-- ')][:6]; print(hdr)
IP=list(recs(g,'ibf_posts')); print('IPB posts',len(IP),rng([r.get('post_date','') for r in IP]), 'topics',len(list(recs(g,'ibf_topics'))),'members',len(list(recs(g,'ibf_members'))),'forums',[(r.get('name','')[:30]) for r in recs(g,'ibf_forums')])
print(' by year',sorted(collections.Counter(d(r['post_date'])[:4] for r in IP if r.get('post_date','').isdigit()).items()))
print(' attachments',len(list(recs(g,'ibf_attachments'))),'pms',len(list(recs(g,'ibf_message_text'))))
