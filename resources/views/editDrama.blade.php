@extends('layout')

@section('content')
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#f97316',
                    background: '#1f2937',
                    color: '#fff'
                });
            });
        </script>
    @endif

    <div class="mt-12 p-8 md:p-12 min-h-screen bg-black text-white w-full flex justify-center items-center">

        @if ($errors->any())
            <div class="bg-red-700 text-white p-4 rounded mb-4">
                <strong>There were some problems with your input:</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="w-full md:max-w-7xl bg-neutral-950 border-1 border-neutral-700 rounded-xl p-6">
            <div class="flex justify-center items-center">
                <h1 class="text-2xl font-bold mb-6">Edit Drama</h1>
            </div>
            <form id="editForm" enctype="multipart/form-data" method="POST"
                action="{{ route('drama.edit.save', ['id' => $details['id']]) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="title" class="block font-semibold">Title</label>
                    <input type="text" name="title" id="title" class="w-full bg-neutral-800 rounded p-2 mt-1 border border-neutral-600" 
                        value="{{ old('title', $details['title']) }}" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block font-semibold">Description</label>
                    <textarea name="description" id="description" rows="4" class="w-full bg-neutral-800 rounded p-2 mt-1 border border-neutral-600" required>{{ old('description', $details['description']) }}</textarea>
                </div>
                <div class="mb-4">
                    <label class="block font-semibold mb-2">Current Images</label>
                    <div class="grid grid-cols-2 gap-4" id="existingImages">
                        @foreach ($details['image'] as $index => $img)
                            <div class="relative">
                                <span
                                    class="absolute top-1 left-1 bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded z-10">
                                    Image {{ $index + 1 }}
                                </span>
                                <button type="button" onclick="removeImage({{ $index }})"
                                    class="absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center z-20">
                                    &times;
                                </button>
                                <img src="{{ asset('storage/' . $img) }}"
                                    class="w-full h-48 object-cover border border-white rounded" />
                                <input type="hidden" name="existing_images[]" value="{{ $img }}"
                                    data-index="{{ $index }}">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="mb-4 w-full">
                    <label for="image" class="block font-semibold mb-2">
                        Images <span class="text-sm text-gray-400">(upload 2: portrait + landscape)</span>
                    </label>

                    <label for="image"
                        class="flex flex-col items-center justify-center w-full px-4 py-6 bg-neutral-800 text-gray-400 rounded-lg shadow-md tracking-wide uppercase border border-neutral-600 cursor-pointer hover:bg-neutral-700 transition duration-200">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5V18a2.25 2.25 0 002.25 2.25h13.5A2.25 2.25 0 0021 18v-1.5M16.5 12L12 16.5m0 0L7.5 12M12 16.5V3">
                            </path>
                        </svg>
                        <span class="mt-2 text-base leading-normal">Select Images</span>
                        <input id="image" type="file" name="image[]" accept="image/*" multiple class="hidden">
                    </label>
                </div>

                <div id="newImagesPreview" class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4"></div>

                {{-- Genres --}}
                <div class="mb-4">
                    <label class="block font-semibold mb-2">Genres</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                        @foreach ($genres as $genre)
                            <label
                                class="flex items-center space-x-2 bg-neutral-800 px-3 py-2 rounded cursor-pointer hover:bg-neutral-700">
                                <input type="checkbox" name="genre[]" value="{{ $genre['id'] }}"
                                    class="form-checkbox text-orange-500 bg-neutral-900 rounded"
                                    @if (collect($details['genres'])->pluck('id')->contains($genre['id'])) checked @endif>
                                <span class="text-wrap break-words">{{ $genre['name'] }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-sm text-gray-400 mt-2">* Pilih satu atau lebih genre yang sesuai.</p>
                </div>

                <div class="flex justify-center items-center">
                    <button type="submit" class="w-full px-6 py-2 bg-orange-500 hover:bg-orange-600 rounded text-white font-bold">
                        Update Drama
                    </button>
                </div>
               
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const imageInput = document.getElementById('image');
        const previewContainer = document.getElementById('newImagesPreview');
        const editForm = document.getElementById('editForm');
        const titleInput = document.getElementById('title');
        const descInput = document.getElementById('description');

        let newFiles = [];

        function removeImage(index) {
            const inputs = document.querySelectorAll('input[name="existing_images[]"]');
            inputs.forEach((input) => {
                if (parseInt(input.dataset.index) === index) {
                    const container = input.closest('div.relative');
                    input.remove();
                    container.remove();
                }
            });
        }

        imageInput.addEventListener('change', () => {
            const files = Array.from(imageInput.files);
            const currentImages = document.querySelectorAll('input[name="existing_images[]"]');
            const total = currentImages.length + newFiles.length + files.length;

            if (total > 2) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Gambar Terlalu Banyak',
                    text: 'Total gambar (lama + baru) tidak boleh lebih dari 2.',
                    background: '#1f2937',
                    color: '#fff'
                });
                imageInput.value = '';
                return;
            }

            // Tambahin ke array+render ulang buat preview
            newFiles = [...newFiles, ...files];
            renderPreview();
            imageInput.value = '';
        });

        function renderPreview() {
            previewContainer.innerHTML = '';
            newFiles.forEach((file, idx) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'relative';

                    const label = document.createElement('span');
                    label.className =
                        'absolute top-1 left-1 bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded z-10';
                    label.textContent = 'New Image ' + (idx + 1);

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className =
                        'absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center z-20';
                    removeBtn.innerHTML = '&times;';
                    removeBtn.onclick = function() {
                        newFiles.splice(idx, 1);
                        renderPreview();
                    };

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-48 object-cover border border-white rounded';

                    wrapper.appendChild(label);
                    wrapper.appendChild(removeBtn);
                    wrapper.appendChild(img);
                    previewContainer.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            });
        }


        editForm.addEventListener('submit', function(e) {
            const title = titleInput.value.trim();
            const desc = descInput.value.trim();
            const genreChecked = document.querySelectorAll('input[name="genre[]"]:checked').length;
            const existingCount = document.querySelectorAll('input[name="existing_images[]"]').length;

            const totalImages = existingCount + newFiles.length;

            if (!title || !desc || genreChecked === 0 || totalImages !== 2) {
                e.preventDefault();
                let message = '';
                if (!title) message += 'Title tidak boleh kosong.\n';
                if (!desc) message += 'Description tidak boleh kosong.\n';
                if (genreChecked === 0) message += 'Minimal satu genre harus dipilih.\n';
                if (totalImages !== 2) message += 'Harus ada tepat 2 gambar (portrait & landscape).';

                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: message,
                    background: '#1f2937',
                    color: '#fff'
                });
                return;
            }

            if (newFiles.length > 0) {
                e.preventDefault();

                // ambil dimensi gambar yg lama
                const existingImages = document.querySelectorAll('input[name="existing_images[]"]');
                const existingImageElements = Array.from(existingImages).map((input) => {
                    const container = input.closest('.relative');
                    const img = container.querySelector('img');
                    return getImageDimensions(img.src);
                });

                const newImagePromises = newFiles.map(readImage);

                Promise.all([...existingImageElements, ...newImagePromises])
                    .then(allImages => {
                        //tentuin image1 dan image2 berdasarkan sisa urutan
                        const imageSlots = [null, null]; // index 0: image 1, index 1: image 2
                        const existingIndexes = Array.from(document.querySelectorAll(
                                'input[name="existing_images[]"]'))
                            .map(input => parseInt(input.dataset.index));

                        //masukkin gambar lama ke slot
                        existingIndexes.forEach((index, i) => {
                            imageSlots[index] = allImages[i];
                            // i =urutan gambar lama di array
                        });

                        //masukkin gambar baru ke slot kosong
                        let newImageIndex = 0;
                        for (let i = 0; i < 2; i++) {
                            if (imageSlots[i] === null) {
                                imageSlots[i] = allImages[existingIndexes.length + newImageIndex];
                                newImageIndex++;
                            }
                        }

                        const img1 = imageSlots[0];
                        const img2 = imageSlots[1];
                        const isPortrait = img1.height > img1.width;
                        const isLandscape = img2.width > img2.height;

                        if (!(isPortrait && isLandscape)) {
                            return showDimError();
                        }

                        const dataTransfer = new DataTransfer();
                        newFiles.forEach(file => dataTransfer.items.add(file));
                        imageInput.files = dataTransfer.files;

                        editForm.submit();
                    });
            }

        });

        function getImageDimensions(src) {
            return new Promise((resolve) => {
                const img = new Image();
                img.onload = function() {
                    resolve(img);
                };
                img.src = src;
            });
        }

        function readImage(file) {
            return new Promise((resolve) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = new Image();
                    img.onload = function() {
                        resolve(img);
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            });
        }

        function showDimError() {
            Swal.fire({
                icon: 'error',
                title: 'Ukuran Gambar Tidak Valid',
                html: 'Gambar pertama harus <b>portrait</b> (tinggi > lebar),<br>gambar kedua harus <b>landscape</b> (lebar > tinggi).',
                confirmButtonColor: '#f97316',
                background: '#1f2937',
                color: '#fff'
            });
        }
    </script>
@endsection
