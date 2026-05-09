<x-layouts.app title="Add New Account">


    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 md:p-8 dark:bg-slate-800 dark:border-slate-700">
            <form action="{{ route('admin.accounts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left: Image Upload -->
                    <div class="lg:col-span-1">
                        <label class="block text-lg font-semibold text-slate-800 dark:text-slate-100 mb-4">Account Images</label>
                        <div class="p-1 border-2 border-dashed border-slate-200 dark:border-slate-600 rounded-2xl bg-slate-50 dark:bg-slate-900/50">
                            <input type="file" id="imageInput" class="filepond" name="images[]" multiple accept="image/png, image/jpeg, image/gif"/>
                        </div>
                        @error('images') <p class="mt-2 text-sm text-red-500 font-medium flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> {{ $message }}</p> @enderror
                        <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">You can upload multiple images for this listing.</p>
                    </div>

                    <!-- Right: Form Details -->
                    <div class="lg:col-span-2 space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Title</label>
                            <input type="text" name="title" value="{{ old('title') }}" 
                                class="w-full px-4 py-3 text-lg rounded-xl border-slate-200 focus:border-primary focus:ring focus:ring-primary/20 transition dark:bg-slate-900 dark:border-slate-600 dark:text-white placeholder:text-slate-400" 
                                placeholder="e.g. Sultan Account Max Level">
                            @error('title') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Category</label>
                                <input type="text" name="category" value="{{ old('category') }}" 
                                    class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-primary focus:ring focus:ring-primary/20 transition dark:bg-slate-900 dark:border-slate-600 dark:text-white"
                                    placeholder="e.g. Mobile Legends">
                                @error('category') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Price (IDR)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 dark:text-slate-400 font-medium">Rp</span>
                                    <input type="number" name="price" value="{{ old('price') }}" 
                                        class="w-full pl-12 pr-4 py-3 rounded-xl border-slate-200 focus:border-primary focus:ring focus:ring-primary/20 transition dark:bg-slate-900 dark:border-slate-600 dark:text-white"
                                        placeholder="150000">
                                </div>
                                @error('price') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Game Username / Login</label>
                                <input type="text" name="username" value="{{ old('username') }}" 
                                    class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-primary focus:ring focus:ring-primary/20 transition dark:bg-slate-900 dark:border-slate-600 dark:text-white placeholder:text-slate-400"
                                    placeholder="Username or Email">
                                @error('username') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Game Password</label>
                                <input type="text" name="password" value="{{ old('password') }}" 
                                    class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-primary focus:ring focus:ring-primary/20 transition dark:bg-slate-900 dark:border-slate-600 dark:text-white placeholder:text-slate-400"
                                    placeholder="Password">
                                @error('password') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Description</label>
                            <textarea name="description" rows="5" 
                                class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-primary focus:ring focus:ring-primary/20 transition dark:bg-slate-900 dark:border-slate-600 dark:text-white placeholder:text-slate-400"
                                placeholder="Describe the account details, skins, rank, etc.">{{ old('description') }}</textarea>
                            @error('description') <p class="mt-2 text-sm text-red-500 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-primary focus:ring focus:ring-primary/20 transition dark:bg-slate-900 dark:border-slate-600 dark:text-white">
                                <option value="available">Available</option>
                                <option value="sold">Sold</option>
                            </select>
                        </div>

                        <div class="pt-6 flex justify-end gap-3 border-t border-slate-100 dark:border-slate-700">
                            <a href="{{ route('admin.accounts.index') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-medium hover:bg-slate-50 transition dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700">Cancel</a>
                            <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg shadow-blue-200/50 transition transform active:scale-95">Save Account</button>
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
        });
    </script>


    @endpush
</x-layouts.app>
