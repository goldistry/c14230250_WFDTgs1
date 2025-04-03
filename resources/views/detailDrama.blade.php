@extends('layout')

@section('content')
    <div class="mt-12 px-8 md:px-32 py-8 md:py-12 bg-black text-white flex justify-center items-center">
        <div
            class="md:mt-10 w-full md:max-w-5xl py-12 px-6 rounded-lg bg-neutral-800 space-y-6 flex flex-col justify-center items-center">
            <h1 class="text-3xl font-bold text-orange-500 text-center">{{ $details['title'] }}</h1>
            <div class="aspect-[3/4] border border-white rounded overflow-hidden w-full max-w-xs">
                <img src="{{ asset('storage/' . $details['image'][0]) }}" alt="{{ $details['title'] }}"
                    class="object-cover w-full h-full" />
            </div>
            <div class="w-full  flex flex-col justify-center items-center">
                <h2 class="text-xl font-semibold mb-2">Genres:</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach ($details['genres'] as $genre)
                        <span class="bg-orange-500 text-white text-sm font-medium px-3 py-1 rounded-full">
                            {{ $genre['name'] }}
                        </span>
                    @endforeach
                </div>
            </div>
            <div class="w-full flex flex-col justify-center items-center">
                <h2 class="text-xl font-semibold mb-2">Description:</h2>
                <p class="text-white leading-relaxed text-justify">
                    {{ $details['description'] }}
                </p>
            </div>
            <div class="flex gap-x-3 items-center justify-center">
                <a href="{{ route('drama.edit', ['id' => $details['id']]) }}"
                    class="px-2 py-2 bg-orange-500 hover:bg-orange-600 rounded text-white font-bold inline-block text-md md:text-lg">
                    Edit Drama
                </a>
                <form id="deleteForm" data-url="{{ route('drama.delete', ['id' => $details['id']]) }}">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete()"
                        class="px-2 py-2 bg-orange-500 hover:bg-orange-600 rounded text-white font-bold inline-block text-md md:text-lg">
                        Delete Drama
                    </button>
                </form>                
            </div>


        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete() {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f97316',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                background: '#1f2937',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    sendDeleteRequest();
                }
            });
        }

        function sendDeleteRequest() {
            const form = document.getElementById('deleteForm');
            const url = form.dataset.url;
            const csrfToken = document.querySelector('input[name="_token"]').value;

            fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            confirmButtonColor: '#f97316',
                            background: '#1f2937',
                            color: '#fff'
                        }).then(() => {
                            window.location.href = "{{ route('homepage') }}";
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Something went wrong!',
                            confirmButtonColor: '#f97316',
                            background: '#1f2937',
                            color: '#fff'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat menghapus data.',
                        background: '#1f2937',
                        color: '#fff'
                    });
                    console.error('Error:', error);
                });
        }
    </script>
@endsection
