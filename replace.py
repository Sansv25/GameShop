import os
import re

def replace_in_file(path):
    with open(path, 'r', encoding='utf-8') as f:
        content = f.read()

    original = content
    
    # Replace logo wrapper with just the logo-rectangle
    content = re.sub(
        r'<a[^>]*href="\{\{\s*route\(\'home\'\)\s*\}\}"[^>]*>[\s\S]*?(?:<div[^>]*>[\s\S]*?</div\s*>|<svg[^>]*>[\s\S]*?</svg>)\s*<span[^>]*>Game\s*<span[^>]*>Shop</span>\s*</span>\s*</a>',
        r'<a href="{{ route(\'home\') }}" class="flex items-center group">\n    <img src="{{ asset(\'asset/logo-rectangle.png\') }}" alt="Logo" class="h-9 transition-transform group-hover:scale-105">\n</a>',
        content,
        flags=re.IGNORECASE
    )
    
    content = re.sub(
        r'<span[^>]*>Game\s*<span[^>]*>Shop</span>\s*</span>',
        r'<img src="{{ asset(\'asset/logo-rectangle.png\') }}" alt="Logo" class="h-8">',
        content,
        flags=re.IGNORECASE
    )
    
    # Replace all gradients starting with bg-gradient-to... to a single color, e.g. bg-blue-600
    content = re.sub(r'bg-gradient-to-[a-z]+\s+from-[a-z]+-\d+\s+to-[a-z]+-\d+', 'bg-blue-600', content)
    content = re.sub(r'bg-gradient-to-[a-z]+\s+from-[a-z]+-\d+\s+via-[a-z]+-\d+\s+to-[a-z]+-\d+', 'bg-blue-600', content)
    
    # Remove from-xxx to-xxx inside hover or normal
    # It might be simpler to just replace indigo, purple, emerald
    content = re.sub(r'indigo-[456]00', 'blue-500', content)
    content = re.sub(r'purple-[456]00', 'blue-500', content)
    content = re.sub(r'indigo-[89]00', 'blue-800', content)
    content = re.sub(r'purple-[89]00', 'blue-800', content)
    content = re.sub(r'indigo-[23]00', 'blue-300', content)
    content = re.sub(r'purple-[23]00', 'blue-300', content)
    content = re.sub(r'emerald-[456]00', 'blue-400', content)

    # In style tags, replace linear-gradient and radial-gradient
    content = re.sub(r'linear-gradient\([^)]+\)', '#2563eb', content)
    content = re.sub(r'radial-gradient\([^)]+\)', 'none', content)

    if content != original:
        with open(path, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f'Updated {path}')

for root, dirs, files in os.walk('resources/views'):
    for file in files:
        if file.endswith('.blade.php'):
            replace_in_file(os.path.join(root, file))
