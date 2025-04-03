@extends('layout')
@section('content')
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <div x-data="{
        ...genreTabs({{ json_encode($genres) }}),
        searchTerm: '',
        filteredDramas() {
            return {{ json_encode($dramas) }}.filter(drama => {
                const title = drama.title.toLowerCase();
                const matchesSearch = this.searchTerm === '' || title.includes(this.searchTerm.toLowerCase());
                const matchesGenre = this.activeGenre === 'all' || drama.genres.some(g => g.id === this.activeGenre);
                return matchesSearch && matchesGenre;
            });
        }
    }" x-init="init()" class="bg-black w-screen min-h-screen text-white">
        @include('navbar')
        <style>
            .swiper-pagination-bullet {
                background: #4b5563 !important; 
                opacity: 1;
            }
        
            .swiper-pagination-bullet-active {
                background: #f97316 !important;
            }
        </style>
        

        <div class="relative w-screen h-[90vh] bg-black">
            <div class="swiper-container w-full h-full">
                <div class="swiper-wrapper">

                    @php
                        $selectedTitles = [
                            'The Untamed',
                            'Love Between Fairy and Devil',
                            'Reset',
                            'You Are My Glory',
                            'The Long Ballad',
                        ];
                        $filteredDramas = collect($dramas)->filter(
                            fn($drama) => in_array($drama['title'], $selectedTitles),
                        );
                    @endphp

                    @foreach ($filteredDramas as $drama)
                        <div class="swiper-slide flex items-center justify-center relative w-full h-[500px]">
                            @if (isset($drama['image'][1]))
                                <img src="{{ asset('storage/' . $drama['image'][1]) }}" alt="{{ $drama['title'] }}"
                                    class="object-contain w-full h-full max-h-full z-0" />

                                <div class="pointer-events-none absolute inset-0 z-10"
                                    style="
                  background:
                    /* Top dan Bottom */
                    linear-gradient(to bottom, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 40%, rgba(0,0,0,0) 80%, rgba(0,0,0,1) 100%),
                    /* Kiri dan Kanan */
                    linear-gradient(to right, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 40%, rgba(0,0,0,0) 80%, rgba(0,0,0,1) 100%);
                  background-blend-mode: multiply;
                ">
                                </div>
                            @else
                                <p class="text-white z-20">No image available</p>
                            @endif
                        </div>
                    @endforeach

                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>

        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

        <script>
            function genreTabs(allGenres) {
                return {
                    genres: allGenres,
                    activeGenre: 'all',
                    showAllGenres: false,
                    screenWidth: window.innerWidth,

                    get visibleGenresCount() {
                        if (this.screenWidth < 640) return 4;
                        if (this.screenWidth < 768) return 4;
                        if (this.screenWidth < 1024) return 5;
                        return 8;
                    },

                    get visibleGenres() {
                        return this.genres.slice(0, this.visibleGenresCount);
                    },

                    get hiddenGenres() {
                        return this.genres.length > this.visibleGenresCount ? this.genres.slice(this.visibleGenresCount) :
                        [];
                    },

                    init() {
                        window.addEventListener('resize', () => {
                            this.screenWidth = window.innerWidth;
                        });
                    },

                    enableDropdownScroll() {
                        this.$nextTick(() => {
                            const dropdown = this.$refs.moreDropdown;
                            if (dropdown) {
                                dropdown.addEventListener('wheel', function(e) {
                                    const el = this;
                                    const scrollTop = el.scrollTop;
                                    const scrollHeight = el.scrollHeight;
                                    const offsetHeight = el.offsetHeight;
                                    const delta = e.deltaY;

                                    const up = delta < 0;
                                    const down = delta > 0;

                                    const atTop = scrollTop === 0;
                                    const atBottom = scrollTop + offsetHeight >= scrollHeight - 1;

                                    if ((up && atTop) || (down && atBottom)) {
                                        e.preventDefault();
                                        e.stopPropagation();
                                    }
                                }, {
                                    passive: false
                                });
                            }
                        });
                    }
                };
            }
        </script>

        <div class="text-white px-6 md:px-26" x-data="genreTabs({{ json_encode($genres) }})" x-init="init()">

            <div class="flex flex-wrap items-center gap-4 pb-2">

                <button @click="activeGenre = 'all'" class="px-4 py-1 rounded-full text-sm whitespace-nowrap"
                    :class="activeGenre === 'all' ? 'text-orange-500 font-bold border-b-2 border-orange-500' :
                        'text-white hover:text-orange-400'">
                    All
                </button>

                <template x-for="genre in visibleGenres" :key="genre.id">
                    <button @click="activeGenre = genre.id" class="px-4 py-1 rounded-full text-sm whitespace-nowrap"
                        :class="activeGenre === genre.id ? 'text-orange-500 font-bold border-b-2 border-orange-500' :
                            'text-white hover:text-orange-400'"
                        x-text="genre.name"></button>
                </template>

                <div class="relative" x-show="showAllGenres || genres.length > visibleGenresCount">
                    <button @click="showAllGenres = !showAllGenres; enableDropdownScroll()"
                        class="px-4 py-1 bg-neutral-800 rounded-lg flex items-center gap-1">
                        <span>More</span>
                        <svg class="w-4 h-4 transform" :class="{ 'rotate-180': showAllGenres }" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.937a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div id="more" x-ref="moreDropdown" x-show="showAllGenres" @click.outside="showAllGenres = false"
                        class="absolute z-50 mt-2 bg-neutral-900 shadow-lg rounded-lg py-2 w-40 max-h-48 overflow-y-auto" x-transition>
                        <template x-for="genre in hiddenGenres" :key="'more-' + genre.id">
                            <button @click="activeGenre = genre.id; showAllGenres = false"
                                class="block w-full text-left px-4 py-2 text-sm text-white hover:bg-neutral-700"
                                :class="activeGenre === genre.id ? 'text-orange-500 font-bold' : ''"
                                x-text="genre.name"></button>
                        </template>
                    </div>
                </div>
            </div>


            <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
            <script>
                var swiper = new Swiper('.swiper-container', {
                    loop: true,
                    autoplay: {
                        delay: 3000,
                        disableOnInteraction: false
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true
                    },
                });
            </script>

            <!-- List dramas -->
            <div class="mt-14 grid grid-cols-2 sm:grid-cols-4 xl:grid-cols-6 gap-6 p-5">
                <template x-for="drama in filteredDramas()" :key="drama.id">
                    <div
                        class="p-2 shadow-lg bg-black border-white border transition duration-300 ease-in-out transform hover:-translate-y-4 hover:border-orange-400">

                        <div class="aspect-[3/4] relative overflow-hidden flex items-center justify-center bg-black w-full">
                            <img :src="'/storage/' + drama.image[0]" :alt="drama.title"
                                class="object-contain w-full h-full z-0" />
                            <div class="pointer-events-none absolute inset-0 z-10"
                                style="
             background:
               linear-gradient(to bottom, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 40%, rgba(0,0,0,0) 80%, rgba(0,0,0,1) 100%),
               linear-gradient(to right, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 40%, rgba(0,0,0,0) 80%, rgba(0,0,0,1) 100%);
             background-blend-mode: multiply;
           ">
                            </div>
                        </div>
                        <h2
                            class="break-words text-wrap ml-1 text-sm lg:text-lg text-[var(--bblue)] cursor-pointer hover:text-orange-500 mt-2">
                            <a :href="'/drama/detail/' + drama.id" x-text="drama.title"></a>
                        </h2>
                    </div>
                </template>
            </div>
        </div>


        <!-- Button add drama -->
        <div class="fixed bottom-6 right-6 z-50 group">
            <a href="{{ route('drama.add') }}"
                class="bg-orange-500 hover:bg-orange-600 text-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </a>
            <!-- tooltip bubble -->
            <div
                class="absolute right-16 bottom-[150%] translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-neutral-800 text-white text-sm px-4 py-2 rounded-md shadow-md whitespace-nowrap pointer-events-none">
                Add a new drama
            </div>
        </div>

    </div>
@endsection
