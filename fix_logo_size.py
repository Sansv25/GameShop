import os

def fix_file(path):
    with open(path, 'r', encoding='utf-8') as f:
        content = f.read()

    original = content
    content = content.replace('class="h-8"', 'class="h-12 md:h-14"')
    content = content.replace('class="h-8 w-auto"', 'class="h-12 md:h-14 w-auto"')
    content = content.replace('class="h-9 transition-transform group-hover:scale-105"', 'class="h-12 md:h-14 transition-transform group-hover:scale-105"')
    content = content.replace('class="h-10 w-auto"', 'class="h-12 md:h-14 w-auto"')

    if content != original:
        with open(path, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f'Fixed {path}')

for root, dirs, files in os.walk('resources/views'):
    for file in files:
        if file.endswith('.blade.php'):
            fix_file(os.path.join(root, file))
