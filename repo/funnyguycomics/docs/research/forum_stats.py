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
t=open(g,encoding='latin-1').read()
def icols(tab):
    m=re.search(r"CREATE TABLE `?"+tab+r"`? \((.*?)\n\) ",t,re.S); return [re.match(r"\s*`(\w+)`",l).group(1) for l in m.group(1).split('\n') if re.match(r"\s*`\w+`",l)]
def irows(tab):
    c=icols(tab)
    for m in re.finditer(r"INSERT INTO `?"+tab+r"`? VALUES ?\((.*)\);\n",t):
        v=[a if a!='' else b for a,b in Q.findall(m.group(1))]
        if len(v)==len(c): yield dict(zip(c,v))
IP=list(irows('ibf_posts')); ts=[int(r['post_date']) for r in IP if r['post_date'].isdigit()]
print('IPB posts',len(IP),d(min(ts)),'->',d(max(ts)),'topics',len(list(irows('ibf_topics'))),'members',len(list(irows('ibf_members'))),'pms',len(list(irows('ibf_message_text'))))
print(' forums',[(r['name'][:28],r['posts']) for r in irows('ibf_forums')])
print(' by month',sorted(collections.Counter(d(x)[:7] for x in ts).items()))
