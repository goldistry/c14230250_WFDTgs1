<nav id="navbar" class="fixed top-0 left-0 w-full z-50 bg-transparent transition-colors duration-300">
    <div class="px-6 md:px-10 mx-auto py-4 flex justify-between items-center">
        <div class="flex justify-center items-center gap-x-2 md:gap-x-4">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="h-6 md:h-10 w-auto">
            <h1 class="questrial-title text-md sm:text-lg md:text-2xl text-white">Lotus Tales</h1>
        </div>

        <!-- Search bar cuman di homepage -->
        @if (Request::is('/'))
        <div class="flex items-center h-6 md:h-10">
            <input 
                type="text"
                x-model="searchTerm"
                placeholder="Search..."
                class="border text-white border-[#efaaaa]-400 bg-black rounded px-3 py-1 focus:outline-none placeholder-gray-400"
            />
        </div>
        @else
            <div class="flex items-center h-6 md:h-10 text-white justify-center">
                <a href="{{ route('homepage') }}"
                    class="text-lg lg:text-xl bg-orange-500 rounded-xl px-3 py-1 hover:bg-white hover:text-orange-500 font-semibold"><i
                        class="fa-solid fa-house"></i></a>
            </div>
        @endif
    </div>
</nav>

<script>
    window.addEventListener('scroll', function() {
        const navbar = document.getElementById('navbar');
        if (window.scrollY > 0) {
            navbar.classList.remove('bg-transparent');
            navbar.classList.add('bg-black');
        } else {
            navbar.classList.remove('bg-black');
            navbar.classList.add('bg-transparent');
        }
    });
</script>
