import os

def revert_chat_theme(path):
    with open(path, 'r', encoding='utf-8') as f:
        content = f.read()

    original = content
    
    # Revert primary color in tailwind config if present
    content = content.replace("primary: '#0ea5e9'", "primary: '#8b5cf6'")
    
    # Common color replacements
    replacements = {
        'bg-blue-600': 'bg-violet-600',
        'bg-blue-500': 'bg-violet-500',
        'bg-blue-400': 'bg-violet-400',
        'text-blue-400': 'text-violet-400',
        'text-blue-500': 'text-violet-500',
        'text-blue-600': 'text-violet-600',
        'border-blue-400': 'border-violet-400',
        'shadow-blue-400': 'shadow-violet-400',
        'bg-emerald-100': 'bg-indigo-100',
        'dark:bg-emerald-900': 'dark:bg-indigo-900',
        'border-emerald-200': 'border-indigo-200',
        'bg-sky-600': 'bg-indigo-600',
        'bg-emerald-50': 'bg-indigo-50',
        'dark:bg-emerald-900/20': 'dark:bg-indigo-900/20',
        'border-emerald-100': 'border-indigo-100',
        'shadow-emerald-100': 'shadow-indigo-100',
        'bg-blue-900/20': 'bg-violet-900/20',
    }
    
    for old, new in replacements.items():
        content = content.replace(old, new)

    if content != original:
        with open(path, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f'Reverted theme in {path}')

chat_files = [
    'resources/views/chat/show.blade.php',
    'resources/views/admin/chat/index.blade.php'
]

for file in chat_files:
    if os.path.exists(file):
        revert_chat_theme(file)
