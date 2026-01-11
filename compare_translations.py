import json

# Read existing translations
with open('lang/ar.json', 'r', encoding='utf-8') as f:
    existing = json.load(f)

existing_keys = set(existing.keys())

# Read all translation keys
all_keys = set()

# Read single-quoted keys
with open('all_single_keys.txt', 'r', encoding='utf-8') as f:
    for line in f:
        key = line.strip()
        if key:
            all_keys.add(key)

# Read double-quoted keys
with open('all_double_keys.txt', 'r', encoding='utf-8') as f:
    for line in f:
        key = line.strip()
        if key:
            all_keys.add(key)

# Add trans_choice keys
all_keys.add('{1} comment|[2,*] comments')
all_keys.add('{1} vote|[2,*] votes')

# Find new keys
new_keys = sorted(all_keys - existing_keys)

print(f'Total unique translation keys found: {len(all_keys)}')
print(f'Already translated in ar.json: {len(existing_keys & all_keys)}')
print(f'New keys needing translation: {len(new_keys)}')
print('\n' + '='*80)
print('NEW TRANSLATION KEYS THAT NEED ARABIC TRANSLATIONS')
print('='*80 + '\n')

for i, key in enumerate(new_keys, 1):
    print(f'{i}. {key}')
