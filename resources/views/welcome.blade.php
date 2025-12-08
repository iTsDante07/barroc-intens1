<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Barroc Intens - De Ultieme Koffie Ervaring</title>

        <link rel="icon" href="/favicon.ico" sizes="any">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Custom Animations */
            .animate-title {
                animation: slideDown 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
                opacity: 0;
                transform: translateY(-30px);
            }

            .animate-text {
                animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
                opacity: 0;
                transform: translateY(20px);
                animation-delay: 0.2s;
            }

            /* Smooth fadeIn for the image */
            .animate-fade-in {
                animation: fadeIn 1.2s ease-out forwards;
                opacity: 0;
                animation-delay: 0.4s;
            }

            @keyframes slideDown {
                to { opacity: 1; transform: translateY(0); }
            }

            @keyframes fadeInUp {
                to { opacity: 1; transform: translateY(0); }
            }

            @keyframes fadeIn {
                to { opacity: 1; }
            }
        </style>
    </head>
    <body class="bg-[#1a1a1a] text-white font-sans antialiased">

        <div class="min-h-screen flex flex-col relative overflow-hidden">

            <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
                <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] bg-[#ffd700] rounded-full opacity-[0.03] blur-[100px]"></div>
                <div class="absolute bottom-[-10%] left-[-10%] w-[600px] h-[600px] bg-[#ffd700] rounded-full opacity-[0.02] blur-[120px]"></div>
            </div>

            <header class="w-full container mx-auto px-6 py-8 flex items-center justify-between z-20">
                <div class="flex items-center gap-3 select-none">
                    <div class="w-10 h-10 bg-gradient-to-tr from-[#ffd700] to-[#e6c200] rounded-lg flex items-center justify-center text-black font-extrabold shadow-lg shadow-[#ffd700]/20">
                        B
                    </div>
                    <span class="text-xl font-bold tracking-tight text-white">
                        Barroc <span class="text-[#ffd700]">Intens</span>
                    </span>
                </div>

                @if (Route::has('login'))
                    <nav>
                        @auth
                            <a href="{{ url('/dashboard') }}"
                               class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-semibold text-[#1a1a1a] bg-[#ffd700] rounded-lg hover:bg-[#ffed4a] hover:scale-105 transform transition-all duration-200 shadow-lg shadow-[#ffd700]/10">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium text-[#ffd700] border border-[#ffd700]/30 bg-[#ffd700]/5 rounded-lg hover:bg-[#ffd700] hover:text-[#1a1a1a] transition-all duration-200 backdrop-blur-sm">
                                Log in
                            </a>
                        @endauth
                    </nav>
                @endif
            </header>

            <main class="flex-grow flex items-center justify-center container mx-auto px-6 z-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center w-full max-w-7xl py-12">

                    <div class="space-y-8 text-center lg:text-left">
                        <h1 class="animate-title text-5xl sm:text-6xl lg:text-7xl font-bold leading-[1.1] tracking-tight">
                            Koffie zoals het <br class="hidden lg:block" />
                            <span class="text-[#ffd700]">
                                bedoeld is.
                            </span>
                        </h1>

                        <div class="space-y-8">
                            <p class="animate-text text-lg text-gray-400 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                                Ervaar de kracht van pure kwaliteit. Barroc Intens levert premium koffiemachines en de fijnste bonen voor bedrijven die alleen genoegen nemen met het beste.
                            </p>

                            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                                @if (Route::has('register'))
                                    <a href="{{ route('login') }}" class="px-8 py-4 bg-[#ffd700] text-[#1a1a1a] font-bold rounded-lg hover:bg-[#ffed4a] transition-colors shadow-lg shadow-[#ffd700]/20">
                                        Offerte Aanvragen
                                    </a>
                                @endif
                                <a href="#products" class="px-8 py-4 bg-[#2a2a2a] text-white border border-gray-700 font-medium rounded-lg hover:border-gray-500 transition-colors">
                                    Bekijk Producten
                                </a>
                            </div>

                            <div class="pt-4 flex items-center justify-center lg:justify-start gap-6 text-sm text-gray-500">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-[#ffd700]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span>Direct Trade</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-[#ffd700]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span>24/7 Service</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative animate-fade-in lg:h-[600px] flex items-center justify-center">
                        <div class="absolute w-[90%] h-[90%] border border-[#ffd700]/20 rounded-full animate-[spin_20s_linear_infinite]"></div>
                        <div class="absolute w-[85%] h-[85%] border border-[#ffd700]/10 rounded-full animate-[spin_30s_linear_infinite_reverse]"></div>

                        <div class="relative w-full max-w-md aspect-[4/5] rounded-2xl overflow-hidden shadow-2xl shadow-black/50 border border-gray-800 group">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent z-10"></div>

                            <img
                                src="https://images.unsplash.com/photo-1497935586351-b67a49e012bf?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                                alt="Barroc Intens Coffee"
                                class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-out"
                            />

                            <div class="absolute bottom-0 left-0 p-8 z-20 w-full">
                                <h3 class="text-white text-2xl font-bold mb-1">Passie voor Koffie</h3>
                                <p class="text-gray-300 text-sm">Sinds 2022 uw partner in kwaliteit.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </main>

            <footer class="w-full py-6 text-center text-gray-500 text-sm border-t border-gray-800/50 bg-[#1a1a1a]/50 backdrop-blur-sm z-20">
                <div class="container mx-auto px-6">
                    &copy; {{ date('Y') }} Barroc Intens. All rights reserved.
                </div>
            </footer>

        </div>
    </body>
</html>
