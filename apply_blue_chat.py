import os

def apply_blue_chat_theme(path):
    with open(path, 'r', encoding='utf-8') as f:
        content = f.read()

    original = content
    
    # Revert primary color back to blue
    content = content.replace("primary: '#8b5cf6'", "primary: '#0ea5e9'")
    
    # Common color replacements (Violet/Indigo back to Blue)
    replacements = {
        'bg-violet-600': 'bg-blue-600',
        'bg-violet-500': 'bg-blue-500',
        'bg-violet-400': 'bg-blue-400',
        'text-violet-400': 'text-blue-400',
        'text-violet-500': 'text-blue-500',
        'text-violet-600': 'text-blue-600',
        'border-violet-400': 'border-blue-400',
        'shadow-violet-400': 'shadow-blue-400',
        'bg-indigo-100': 'bg-blue-100',
        'dark:bg-indigo-900': 'dark:bg-blue-900',
        'border-indigo-200': 'border-blue-200',
        'bg-indigo-600': 'bg-blue-600',
        'bg-indigo-50': 'bg-blue-50',
        'dark:bg-indigo-900/20': 'dark:bg-blue-900/20',
        'border-indigo-100': 'border-blue-100',
        'shadow-indigo-100': 'shadow-blue-100',
        'bg-violet-900/20': 'bg-blue-900/20',
        'border-violet-200': 'border-blue-200',
        'border-violet-800': 'border-blue-800',
        'text-violet-500': 'text-blue-500',
    }
    
    for old, new in replacements.items():
        content = content.replace(old, new)

    # Specific fix for "lawan bicara" text color to white in chat/show.blade.php
    # We find the message bubble logic
    if 'chat/show.blade.php' in path:
        # Re-apply the classes for other person's bubble to ensure white text
        # Old: :class="msg.sender_id == {{ auth()->id() }} ? '...' : (msg.is_error_message ? '...' : (msg.is_auto_message ? '... text-slate-800 dark:text-slate-200' : 'bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 ...'))"
        # We want other person's messages to have white text.
        content = content.replace('text-slate-800 dark:text-slate-200', 'text-white')
        content = content.replace('text-slate-800 dark:white', 'text-white')

    if content != original:
        with open(path, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f'Applied Blue theme and white text in {path}')

chat_files = [
    'resources/views/chat/show.blade.php',
    'resources/views/admin/chat/index.blade.php'
]

for file in chat_files:
    if os.path.exists(file):
        apply_blue_chat_theme(file)
