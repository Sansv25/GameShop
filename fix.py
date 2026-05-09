import os

def fix_file(path):
    with open(path, 'r', encoding='utf-8') as f:
        content = f.read()

    original = content
    content = content.replace("route(\\'home\\')", "route('home')")

    if content != original:
        with open(path, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f'Fixed {path}')

for root, dirs, files in os.walk('resources/views'):
    for file in files:
        if file.endswith('.blade.php'):
            fix_file(os.path.join(root, file))
