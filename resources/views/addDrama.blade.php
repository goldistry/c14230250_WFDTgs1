@extends('layout')

@section('content')
    <div class="mt-12 p-6 sm:p-12 min-h-screen text-white w-full flex items-center justify-center">

        <div class="w-full md:max-w-7xl bg-neutral-950 border-1 border-neutral-700 rounded-xl p-8">
            <div class="flex justify-center items-center">
                <h1 class="text-2xl font-bold mb-6">Add New Drama</h1>
            </div>
            <form id="promotionForm">
                @csrf

                <div class="mb-4">
                    <label for="title" class="block font-semibold">Title</label>
                    <input type="text" name="title" id="title" placeholder="Enter drama title"
                        class="w-full bg-neutral-800 rounded border border-neutral-600 px-3 py-2 mt-1 text-white" value="{{ old('title') }}" required>
                </div>

                <div class="mb-4">
                    <label for="description" class="block font-semibold">Description</label>
                    <textarea name="description" id="description" rows="4" placeholder="Describe the drama" class="w-full bg-neutral-800 rounded border border-neutral-600 px-3 py-2 mt-1 text-white"
                        required>{{ old('description') }}</textarea>
                </div>

                <div class="mb-4 w-full">
                    <label for="image" class="block font-semibold mb-2">
                        Images <span class="text-sm text-gray-400">(upload 2: portrait + landscape)</span>
                    </label>
                    <label for="image"
                        class="flex flex-col items-center justify-center w-full px-4 py-6 bg-neutral-800 text-gray-400 rounded-lg shadow-md tracking-wide uppercase border border-neutral-800 cursor-pointer hover:bg-neutral-700 transition duration-200">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5V18a2.25 2.25 0 002.25 2.25h13.5A2.25 2.25 0 0021 18v-1.5M16.5 12L12 16.5m0 0L7.5 12M12 16.5V3">
                            </path>
                        </svg>
                        <span class="mt-2 text-base leading-normal">Select Images</span>
                        <input id="image" type="file" name="image[]" accept="image/*" multiple class="hidden " >
                    </label>
                    <div id="previewContainer" class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4"></div>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-2">Genres</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                        @foreach ($genres as $genre)
                            <label
                                class="flex items-center space-x-2 bg-neutral-800 px-3 py-2 rounded cursor-pointer hover:bg-neutral-700">
                                <input type="checkbox" name="genre[]" value="{{ $genre['id'] }}"
                                    class="form-checkbox text-orange-500 bg-neutral-900 rounded">
                                <span class="text-wrap break-words">{{ $genre['name'] }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-sm text-gray-400 mt-2">* Pilih satu atau lebih genre yang sesuai.</p>
                </div>
                <div class="flex justify-center items-center">
                    <button type="submit" class="w-full px-6 py-2 bg-orange-500 hover:bg-orange-600 rounded text-white font-bold">
                        Add Drama
                    </button>
                </div>

                
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const input = document.getElementById('image');
        const previewContainer = document.getElementById('previewContainer');
        let fileArray = [];

        input.addEventListener('change', function() {
            const newFiles = Array.from(input.files);
            const tempFiles = [...fileArray, ...newFiles];

            if (tempFiles.length > 2) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Gambar Terlalu Banyak',
                    text: 'Silakan upload hanya 2 gambar: 1 portrait dan 1 landscape.',
                    confirmButtonColor: '#f97316',
                    background: '#1f2937',
                    color: '#fff'
                });
                input.value = '';
                return;
            }

            fileArray = tempFiles;
            renderPreviews();
            updateFileInput();
        });

        function renderPreviews() {
            previewContainer.innerHTML = '';
            fileArray.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('div');
                    preview.className = 'relative rounded overflow-hidden border border-white';
                    preview.innerHTML = `
                        <span class="absolute top-1 left-1 bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded z-10">Image ${index + 1}</span>
                        <button type="button" class="absolute top-1 right-1 text-white bg-red-600 hover:bg-red-700 rounded-full w-6 h-6 flex items-center justify-center text-xs z-10" onclick="removeImage(${index})">✕</button>
                        <img src="${e.target.result}" class="w-full h-48 object-cover" />`;
                    previewContainer.appendChild(preview);
                };
                reader.readAsDataURL(file);
            });
        }

        function updateFileInput() {
            const dataTransfer = new DataTransfer();
            fileArray.forEach(file => dataTransfer.items.add(file));
            input.files = dataTransfer.files;
        }

        function removeImage(index) {
            fileArray.splice(index, 1);
            renderPreviews();
            updateFileInput();
        }

        const form = document.getElementById('promotionForm');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const title = document.getElementById('title').value.trim();
            const description = document.getElementById('description').value.trim();
            const genreChecked = document.querySelectorAll('input[name="genre[]"]:checked').length;

            fileArray = Array.from(input.files);

            if (!title || !description || genreChecked === 0 || fileArray.length !== 2) {
                let message = '';
                if (!title) message += 'Title tidak boleh kosong.\n';
                if (!description) message += 'Description tidak boleh kosong.\n';
                if (genreChecked === 0) message += 'Minimal satu genre harus dipilih.\n';
                if (fileArray.length !== 2) message += 'Harus ada tepat 2 gambar (portrait & landscape).';

                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: message,
                    background: '#1f2937',
                    color: '#fff',
                    confirmButtonColor: '#f97316'
                });
                return;
            }

            const reader1 = new FileReader();
            const reader2 = new FileReader();

            reader1.onload = function(e1) {
                const img1 = new Image();
                img1.onload = function() {
                    const isPortrait = img1.height > img1.width;

                    reader2.onload = function(e2) {
                        const img2 = new Image();
                        img2.onload = function() {
                            const isLandscape = img2.width > img2.height;

                            if (!isPortrait || !isLandscape) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Ukuran Gambar Tidak Valid',
                                    html: 'Gambar pertama harus <b>portrait</b> (tinggi > lebar),<br>gambar kedua harus <b>landscape</b> (lebar > tinggi).',
                                    confirmButtonColor: '#f97316',
                                    background: '#1f2937',
                                    color: '#fff'
                                });
                                return;
                            }

                            const formData = new FormData();
                            formData.append('title', title);
                            formData.append('description', description);
                            fileArray.forEach(f => formData.append('image[]', f));
                            const selectedGenres = Array.from(document.querySelectorAll(
                                'input[name="genre[]"]:checked')).map(cb => cb.value);
                            formData.append('genres', JSON.stringify(selectedGenres));

                            fetch("{{ route('drama.add.save') }}", {
                                    method: "POST",
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: formData
                                })
                                .then(async response => {
                                    if (!response.ok) {
                                        const errorData = await response.json();
                                        throw new Error(errorData.message ||
                                            'Gagal mengirim data.');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: 'Drama berhasil ditambahkan.',
                                        confirmButtonColor: '#f97316',
                                        background: '#1f2937',
                                        color: '#fff'
                                    }).then(() => {
                                        window.location.href =
                                            "{{ route('homepage') }}";
                                    });
                                })
                                .catch(error => {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops!',
                                        text: error.message || 'Terjadi kesalahan.',
                                        confirmButtonColor: '#f97316',
                                        background: '#1f2937',
                                        color: '#fff'
                                    });
                                });
                        };
                        img2.src = e2.target.result;
                    };
                    reader2.readAsDataURL(fileArray[1]);
                };
                img1.src = e1.target.result;
            };
            reader1.readAsDataURL(fileArray[0]);
        });
    </script>
@endsection
