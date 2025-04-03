@extends('layout')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-black via-neutral-950 to-black text-gray-300 font-sans antialiased">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
        
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-semibold text-center text-white mb-16 sm:mb-20 tracking-tight animate-fade-in-up-scale delay-[100ms]">
                Choose Your Plan
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10">
                @foreach($subscriptions as $subscription)
                    <div 
                         x-data="{ showDetails: false }"
                         class="relative flex flex-col bg-gradient-to-br from-neutral-900 to-gray-900 rounded-xl p-6 sm:p-8 transition-all duration-500 ease-out border border-neutral-800/80 overflow-hidden group transform hover:scale-105 hover:rotate-3"
                         @click="showDetails = !showDetails"
                    >
                        <div class="relative z-10 flex flex-col flex-grow">
                            <div class="flex-grow">
                                <div class="flex items-center space-x-4 mb-4">
                                    @if($subscription['logo'])
                                        <img src="{{ asset('storage/' . $subscription['logo']) }}" alt="{{ $subscription['name'] }} Logo"
                                             class="w-11 h-11 object-contain rounded-lg bg-neutral-800 p-1 shadow-md">
                                    @else
                                        <div class="w-11 h-11 rounded-lg bg-neutral-800 flex items-center justify-center shadow-md">
                                              <svg class="w-5 h-5 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                        </div>
                                    @endif
                                    <h2 class="text-xl font-semibold text-white">{{ $subscription['name'] }}</h2>
                                </div>

                                <p class="text-neutral-400 text-sm mb-6 h-10">
                                    Explore premium features with the {{ $subscription['name'] }} plan.
                                </p>

                            </div>

                            <div 
                                x-show="showDetails"
                                x-collapse.duration.500ms
                                class="pt-6 mt-6 border-t border-neutral-800/60 group-hover:border-orange-600/30 transition-colors duration-300"
                                @click.stop
                            >
                                <h3 class="text-xs uppercase tracking-wider font-semibold text-neutral-500 mb-4">Pricing</h3>
                                <div class="space-y-4 mb-6">
                                     <div class="grid grid-cols-{{ ($subscription['monthly_price'] && $subscription['yearly_price']) ? '2' : '1' }} gap-4">
                                        @if($subscription['monthly_price'])
                                            <div class="flex flex-col items-center justify-center p-4 bg-neutral-800 rounded-lg">
                                                <span class="text-sm text-neutral-400">Monthly</span>
                                                <span class="mt-2 text-2xl font-bold text-white">{{ $subscription['monthly_price'] }}</span>
                                            </div>
                                        @endif
                                        @if($subscription['yearly_price'])
                                            <div class="flex flex-col items-center justify-center p-4 bg-gradient-to-tr from-orange-600/10 via-neutral-800 to-neutral-800 border border-orange-600/30 rounded-lg relative overflow-hidden">
                                                 <span class="absolute top-1 right-1 text-[10px] bg-orange-500 text-white px-1.5 py-0.5 rounded-full shadow-sm">SAVE</span>
                                                <span class="text-sm text-neutral-400">Yearly</span>
                                                <span class="mt-2 text-2xl font-bold text-white">{{ $subscription['yearly_price'] }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <h3 class="text-xs uppercase tracking-wider font-semibold text-neutral-500 mb-3 mt-6">Features</h3>
                                <ul class="space-y-2 text-sm text-neutral-400">
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-orange-500/80 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Premium Content Access
                                    </li>
                                    <li class="flex items-center">
                                         <svg class="w-4 h-4 mr-2 text-orange-500/80 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        High Quality Streaming
                                    </li>
                                    <li class="flex items-center text-neutral-500">
                                         <svg class="w-4 h-4 mr-2 text-neutral-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                        </svg>
                                        Limited Downloads
                                    </li>
                                </ul>

                                <a href="{{ $subscription['website'] }}" 
                                   class="block w-full mt-8 bg-orange-600 hover:bg-orange-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-neutral-900 focus:ring-orange-400 text-white text-center font-semibold py-3 px-5 rounded-lg transition duration-300 transform hover:scale-[1.02]"
                                   @click.stop
                                >
                                    Subscribe to {{ $subscription['name'] }}
                                </a>
                            </div>
                         </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection