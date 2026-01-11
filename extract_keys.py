import json
import re
import subprocess

# Read existing translations
with open('lang/ar.json', 'r', encoding='utf-8') as f:
    existing = json.load(f)

existing_keys = set(existing.keys())

# Collect all translation keys from grep outputs
all_keys = set()

# Get single-quoted keys
result = subprocess.run(['grep', '-roh', "__('[^']*')", 'resources/views/'],
                       capture_output=True, text=True, encoding='utf-8')
for line in result.stdout.strip().split('\n'):
    if line:
        match = re.search(r"__\('([^']+)'\)", line)
        if match:
            all_keys.add(match.group(1))

# Get double-quoted keys
result = subprocess.run(['grep', '-roh', '__("[^"]*")', 'resources/views/'],
                       capture_output=True, text=True, encoding='utf-8')
for line in result.stdout.strip().split('\n'):
    if line:
        match = re.search(r'__\("([^"]+)"\)', line)
        if match:
            all_keys.add(match.group(1))

# Get trans_choice keys
result = subprocess.run(['grep', '-roh', "trans_choice('[^']*'", 'resources/views/'],
                       capture_output=True, text=True, encoding='utf-8')
for line in result.stdout.strip().split('\n'):
    if line:
        match = re.search(r"trans_choice\('([^']+)'", line)
        if match:
            all_keys.add(match.group(1))

# Find new keys
new_keys = sorted(all_keys - existing_keys)

print(f'Total unique translation keys found: {len(all_keys)}')
print(f'Already translated in ar.json: {len(existing_keys & all_keys)}')
print(f'New keys needing translation: {len(new_keys)}')
print('\n=== NEW TRANSLATION KEYS ===\n')
for key in new_keys:
    print(key)
