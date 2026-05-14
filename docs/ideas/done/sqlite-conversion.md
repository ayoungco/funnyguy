Short answer: use an SQL dump if you want to preserve schema, indexes, and types; use CSV only for a quick data-only move (you’ll have to hand-create the schema and you’ll lose constraints, defaults, and indexes).

Here’s how to do both, plus gotchas.

Option A — SQL dump (recommended)

This keeps tables, indexes, and (most) constraints with minimal manual work.

1) Dump from MySQL (data + schema, but skip MySQL-specific extras):

mysqldump \
  --routines=false --triggers=false --events=false \
  --single-transaction --skip-lock-tables --hex-blob \
  --default-character-set=utf8mb4 \
  your_db > mysql.sql

2) Convert MySQL dialect → SQLite dialect
Use a converter, then load into SQLite:
	•	Popular approach (shell script): mysql2sqlite

curl -L https://raw.githubusercontent.com/dumblob/mysql2sqlite/master/mysql2sqlite | \
  bash -s -- mysql.sql | sqlite3 output.sqlite

	•	Or Python tool (very reliable): db-to-sqlite from the sqlite-utils ecosystem

pip install sqlite-utils
db-to-sqlite "mysql://USER:PASS@HOST/your_db" output.sqlite

(Add --all to pull all tables; use --table table_name to limit.)

Why this is best
	•	Preserves table structure, indexes, and (where possible) FKs.
	•	Handles BLOBs and utf8mb4 correctly.
	•	No manual typing of CREATE TABLE statements.

Option B — CSVs (data only, simple but lossy)

Good for a subset or when you don’t care about schema fidelity.

1) Export each table to CSV:

mysql -N -e "SELECT * FROM your_table" --batch your_db > your_table.tsv   # or use INTO OUTFILE on server

(or use SELECT ... INTO OUTFILE if you have filesystem rights)

2) Create the table in SQLite (manually):

-- in sqlite3
CREATE TABLE your_table (
  id INTEGER PRIMARY KEY,
  name TEXT,
  created_at TEXT
  -- define all columns!
);
.mode tabs              -- or .mode csv if you used CSV
.import your_table.tsv your_table

Downsides
	•	You must define every table by hand.
	•	No indexes, FKs, defaults, or triggers unless you add them yourself.
	•	Need special care for NULLs, dates, and blobs.

Common gotchas (either route)
	•	Types: Map MySQL types to SQLite affinities:
INT→INTEGER, VARCHAR/TEXT→TEXT, DECIMAL/NUMERIC→NUMERIC, BLOB→BLOB.
AUTO_INCREMENT → make the column INTEGER PRIMARY KEY.
	•	Unsigned ints: SQLite ignores UNSIGNED; check ranges if you relied on it.
	•	Booleans/ENUM: Store as INTEGER (0/1) or TEXT.
	•	Zero dates: 0000-00-00 isn’t valid in SQLite’s date functions—store as TEXT or convert to NULL.
	•	Foreign keys: SQLite enforces only if PRAGMA foreign_keys=ON;. Some converters emit FKs at the end—verify.
	•	Views/Procedures/Triggers: MySQL procedures won’t port. Views may port if they’re simple; triggers often need manual tweaks.

Speed tips (SQLite import)

Run these before large inserts:

PRAGMA journal_mode = WAL;
PRAGMA synchronous = NORMAL;
PRAGMA temp_store = MEMORY;
BEGIN;
-- import work ...
COMMIT;

Quick recommendation
	•	If you want a faithful copy with minimal fuss: use SQL + a converter (mysql2sqlite or db-to-sqlite).
	•	If you only need some tables fast and don’t care about schema details: CSV is fine, but expect extra manual work.

If you tell me how big the DB is and whether you need constraints preserved, I can give you exact commands tuned for your case.