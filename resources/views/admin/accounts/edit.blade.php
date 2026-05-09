<x-layouts.app title="Edit Account">


    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 md:p-8 dark:bg-slate-800 dark:border-slate-700">
            <form action="{{ route('admin.accounts.update', $gameAccount) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left: Image Management -->
                    <div class="lg:col-span-1">
                        <label class="block text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Account Images</label>
                        
                            @php
                                $images = $gameAccount->images ?? [];
                                if ($gameAccount->image_path && !in_array($gameAccount->image_path, $images)) {
                                    $images[] = $gameAccount->image_path;
                                }
                                $mainImage = old('main_image_path', $gameAccount->image_path);
                            @endphp
                            <input type="hidden" name="main_image_path" id="mainImageInput" value="{{ $mainImage }}">
                            
                            <div class="grid grid-cols-2 gap-3 mb-6">
                            @foreach($images as $index => $img)
                            <div class="relative group aspect-square rounded-xl overflow-hidden border {{ $img == $mainImage ? 'border-blue-500 ring-2 ring-blue-500/50' : 'border-slate-200 dark:border-slate-700' }} bg-slate-50 dark:bg-slate-900" id="existing-img-{{ $index }}">
                                <img src="{{ Storage::url($img) }}" class="w-full h-full object-cover">
                                <input type="hidden" name="existing_images[]" value="{{ $img }}">
                                
                                <!-- Main Badge -->
                                <div class="absolute top-1.5 left-1.5 main-badge {{ $img == $mainImage ? 'opacity-100' : 'opacity-0 group-hover:opacity-100' }} transition">
                                    <button type="button" onclick="setMainImage('{{ $img }}', 'existing-img-{{ $index }}')" class="px-2 py-1 {{ $img == $mainImage ? 'bg-blue-600' : 'bg-slate-800/80 hover:bg-blue-600' }} text-[10px] text-white font-bold rounded shadow-sm">
                                        {{ $img == $mainImage ? 'MAIN' : 'SET MAIN' }}
                                    </button>
                                </div>

                                <button type="button" onclick="removeExistingImage('existing-img-{{ $index }}')" class="absolute top-1.5 right-1.5 p-1 bg-red-500 text-white rounded-lg opacity-0 group-hover:opacity-100 transition shadow-lg hover:bg-red-600">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                            @endforeach
                            </div>

                        <div class="p-1 border-2 border-dashed border-slate-200 dark:border-slate-600 rounded-2xl bg-slate-50 dark:bg-slate-900/50">
                            <input type="file" id="imageInput" class="filepond" name="images[]" multiple accept="image/png, image/jpeg, image/gif"/>
                        </div>
                        @error('images') <p class="mt-2 text-sm text-red-500 font-medium flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> {{ $message }}</p> @enderror
                        <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">Add more images or replace existing ones.</p>
                    </div>

                    <!-- Right: Form Details -->
                    <div class="lg:col-span-2 space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Title</label>
                            <input type="text" name="title" value="{{ old('title', $gameAccount->title) }}" 
                                class="w-full px-4 py-3 text-lg rounded-xl border-slate-200 focus:border-primary focus:ring focus:ring-primary/20 transition dark:bg-slate-900 dark:border-slate-600 dark:text-white placeholder:text-slate-400">
                            @error('title') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Category</label>
                                <input type="text" name="category" value="{{ old('category', $gameAccount->category) }}" 
                                    class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-primary focus:ring focus:ring-primary/20 transition dark:bg-slate-900 dark:border-slate-600 dark:text-white">
                                @error('category') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Price (IDR)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400 font-medium">Rp</span>
                                    <input type="number" name="price" value="{{ old('price', $gameAccount->price) }}" 
                                        class="w-full pl-12 pr-4 py-3 rounded-xl border-slate-200 focus:border-primary focus:ring focus:ring-primary/20 transition dark:bg-slate-900 dark:border-slate-600 dark:text-white">
                                </div>
                                @error('price') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Game Username / Login</label>
                                <input type="text" name="username" value="{{ old('username', $gameAccount->username) }}" 
                                    class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-primary focus:ring focus:ring-primary/20 transition dark:bg-slate-900 dark:border-slate-600 dark:text-white placeholder:text-slate-400"
                                    placeholder="Username or Email">
                                @error('username') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Game Password</label>
                                <input type="text" name="password" value="{{ old('password', $gameAccount->password) }}" 
                                    class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-primary focus:ring focus:ring-primary/20 transition dark:bg-slate-900 dark:border-slate-600 dark:text-white placeholder:text-slate-400"
                                    placeholder="Password">
                                @error('password') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Description</label>
                            <textarea name="description" rows="5" 
                                class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-primary focus:ring focus:ring-primary/20 transition dark:bg-slate-900 dark:border-slate-600 dark:text-white">{{ old('description', $gameAccount->description) }}</textarea>
                            @error('description') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-primary focus:ring focus:ring-primary/20 transition dark:bg-slate-900 dark:border-slate-600 dark:text-white">
                                <option value="available" {{ $gameAccount->status == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="sold" {{ $gameAccount->status == 'sold' ? 'selected' : '' }}>Sold</option>
                            </select>
                        </div>

                        <div class="pt-6 flex justify-end gap-3 border-t border-slate-100 dark:border-slate-700">
                            <a href="{{ route('admin.accounts.index') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-medium hover:bg-slate-50 transition dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700">Cancel</a>
                            <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg shadow-blue-200/50 transition transform active:scale-95">Update Account</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        window.addEventListener('load', function() {
            if (typeof FilePond === 'undefined') {
                console.error('FilePond not loaded');
                return;
            }

            const inputElement = document.querySelector('input[id="imageInput"]');
            const pond = FilePond.create(inputElement, {
                labelIdle: 'Drag & Drop your images or <span class="filepond--label-action">Browse</span>',
                credits: false,
                allowMultiple: true,
                allowReorder: true,

                // Allow up to 15MB originals — ImageTransform will compress them
                // before upload. maxFileSize validation runs on the ORIGINAL file,
                // so it must be larger than the biggest raw image you expect.
                maxFileSize: '15MB',

                // Image Transform & Resize — compress aggressively client-side
                allowImageResize: true,
                imageResizeTargetWidth: 1920,   // Full-HD is plenty for product images
                imageResizeTargetHeight: 1920,
                imageResizeMode: 'contain',     // Never crop; shrink proportionally
                imageResizeUpscale: false,       // Don't enlarge small images
                allowImageTransform: true,
                imageTransformOutputMimeType: 'image/jpeg',
                imageTransformOutputQuality: 82, // ~82 is the sweet spot: great quality, ~60-70% size reduction

                server: {
                    process: {
                        url: '{{ route('upload') }}',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        onload: (response) => {
                            try {
                                const res = JSON.parse(response);
                                return res.id || response;
                            } catch (e) {
                                return response;
                            }
                        }
                    },
                    revert: {
                        url: '{{ route('revert') }}',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }
                }
            });

            // Re-expose these to window so they can be called from onclick attributes
            window.setMainImage = function(path, elId) {
                document.getElementById('mainImageInput').value = path;
                
                // Update UI
                document.querySelectorAll('[id^="existing-img-"]').forEach(el => {
                    el.classList.remove('border-blue-500', 'ring-2', 'ring-blue-500/50');
                    el.classList.add('border-slate-200', 'dark:border-slate-700');
                    
                    const badge = el.querySelector('.main-badge');
                    if (badge) {
                        const btn = badge.querySelector('button');
                        badge.classList.remove('opacity-100');
                        badge.classList.add('opacity-0');
                        btn.classList.remove('bg-blue-600');
                        btn.classList.add('bg-slate-800/80');
                        btn.innerText = 'SET MAIN';
                    }
                });
                
                const activeEl = document.getElementById(elId);
                activeEl.classList.add('border-blue-500', 'ring-2', 'ring-blue-500/50');
                activeEl.classList.remove('border-slate-200', 'dark:border-slate-700');
                
                const activeBadge = activeEl.querySelector('.main-badge');
                if (activeBadge) {
                    const activeBtn = activeBadge.querySelector('button');
                    activeBadge.classList.remove('opacity-0');
                    activeBadge.classList.add('opacity-100');
                    activeBtn.classList.add('bg-blue-600');
                    activeBtn.classList.remove('bg-slate-800/80');
                    activeBtn.innerText = 'MAIN';
                }
            };

            window.removeExistingImage = function(id) {
                const el = document.getElementById(id);
                if (el) {
                    // If we are removing the main image, reset it
                    const input = el.querySelector('input[name="existing_images[]"]');
                    if (input) {
                        const imgPath = input.value;
                        if (document.getElementById('mainImageInput').value === imgPath) {
                            document.getElementById('mainImageInput').value = '';
                        }
                    }
                    
                    el.classList.add('scale-0', 'opacity-0');
                    setTimeout(() => el.remove(), 300);
                }
            };
        });
    </script>

    @endpush
</x-layouts.app>
