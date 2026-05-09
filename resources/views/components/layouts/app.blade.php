<x-layouts.app.sidebar :title="$title ?? null">
    <x-container class="[grid-area:main] max-w-full py-6 lg:py-8">
        {{ $slot }}
    </x-container>

    @if(session('success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#0ea5e9',
                confirmButtonText: 'Great!'
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                title: 'Error!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Okay'
            });
        </script>
    @endif
</x-layouts.app.sidebar>
