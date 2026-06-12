import subprocess
import re

# Get list of all crm_ tables
result = subprocess.run(
    ['sudo', '-u', 'postgres', 'psql', '-d', 'gopi_portfolio', '-t', '-c',
     "SELECT table_name FROM information_schema.tables WHERE table_schema='public' AND table_name LIKE 'crm_%' AND table_type='BASE TABLE' ORDER BY table_name;"],
    capture_output=True, text=True
)

tables = [t.strip() for t in result.stdout.strip().split('\n') if t.strip()]
print(f"Found {len(tables)} CRM tables")

fixed = []
skipped = []

for table in tables:
    # Try to fix the sequence
    sql = f"""
SELECT setval(
    pg_get_serial_sequence('{table}', 'id'),
    COALESCE((SELECT MAX(id) FROM {table}), 0) + 1,
    false
);
"""
    r = subprocess.run(
        ['sudo', '-u', 'postgres', 'psql', '-d', 'gopi_portfolio', '-t', '-c', sql],
        capture_output=True, text=True
    )
    if r.returncode == 0 and r.stdout.strip():
        val = r.stdout.strip()
        fixed.append(f"  {table}: next_id={val}")
    else:
        skipped.append(f"  {table}: {r.stderr.strip()[:80] if r.stderr else 'no sequence'}")

print("\n=== FIXED ===")
for f in fixed:
    print(f)

print(f"\n=== SKIPPED ({len(skipped)}) ===")
for s in skipped[:5]:
    print(s)

print(f"\nDone. Fixed {len(fixed)} tables.")
