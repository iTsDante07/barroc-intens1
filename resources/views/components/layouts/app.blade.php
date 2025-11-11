<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barroc Intens - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-barroc-yellow { background-color: #FFD700; }
        .text-barroc-yellow { color: #FFD700; }
        .border-barroc-yellow { border-color: #FFD700; }
        .hover\:bg-barroc-yellow:hover { background-color: #FFD700; }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-black text-white shadow-lg">
        <div class="max-w-7xl px-4 ml-16">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/Logo6_groot.png') }}" alt="Barroc Intens" class="w-12 h-12" />
                    <a href="{{ route('dashboard') }}" class="text-barroc-yellow font-bold text-xl hover:text-yellow-400 transition-colors">
                        BARROC INTENS
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <span class="text-barroc-yellow">{{ auth()->user()->name }}</span>
                        <span class="bg-barroc-yellow text-black px-3 py-1 rounded-full text-sm">
                            {{ auth()->user()->department->name ?? 'Geen afdeling' }}
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-white hover:text-barroc-yellow transition-colors">
                                Uitloggen
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar en Main Content -->
    <div class="flex">
        <!-- Sidebar -->
        @auth
        <aside class="w-64 bg-white shadow-lg min-h-screen">
            <nav class="mt-6">
                <div class="px-4 py-2 text-gray-500 text-sm font-bold">NAVIGATIE</div>

                <a href="{{ route('dashboard') }}" class="block px-4 py-3 text-gray-700 hover:bg-barroc-yellow hover:text-black transition-colors {{ request()->routeIs('dashboard') ? 'bg-barroc-yellow text-black' : '' }}">
                    📊 Dashboard
                </a>

                <!-- Sales Links -->
                @if(auth()->user()->department && (auth()->user()->department->name === 'Sales' || auth()->user()->isManager() || auth()->user()->isAdmin()))
                <div class="px-4 py-2 text-gray-500 text-sm font-bold">SALES</div>
                <a href="{{ route('products.index') }}" class="block px-4 py-3 text-gray-700 hover:bg-barroc-yellow hover:text-black transition-colors {{ request()->routeIs('products.index') ? 'bg-barroc-yellow text-black' : '' }}">
                    📦 Producten
                </a>
                <a href="{{ route('customers.index') }}" class="block px-4 py-3 text-gray-700 hover:bg-barroc-yellow hover:text-black transition-colors {{ request()->routeIs('customers.index') ? 'bg-barroc-yellow text-black' : '' }}">
                    👥 Klanten
                </a>
                <a href="{{ route('customers.bkr-check') }}" class="block px-4 py-3 text-gray-700 hover:bg-barroc-yellow hover:text-black transition-colors {{ request()->routeIs('customers.bkr-check') ? 'bg-barroc-yellow text-black' : '' }}">
                    🔍 BKR Check
                </a>
                <a href="{{ route('quotes.index') }}" class="block px-4 py-3 text-gray-700 hover:bg-barroc-yellow hover:text-black transition-colors {{ request()->routeIs('quotes.*') ? 'bg-barroc-yellow text-black' : '' }}">
                    📄 Offertes
                </a>
                @endif

                <!-- Finance Links -->
                @if(auth()->user()->department && (auth()->user()->department->name === 'Finance' || auth()->user()->isManager() || auth()->user()->isAdmin()))
                <div class="px-4 py-2 text-gray-500 text-sm font-bold">FINANCE</div>
                <a href="{{ route('invoices.index') }}" class="block px-4 py-3 text-gray-700 hover:bg-barroc-yellow hover:text-black transition-colors {{ request()->routeIs('invoices.*') ? 'bg-barroc-yellow text-black' : '' }}">
                    🧾 Facturen
                </a>
                @endif

                <!-- Maintenance Links -->
                @if(auth()->user()->department && (auth()->user()->department->name === 'Maintenance' || auth()->user()->isManager() || auth()->user()->isAdmin()))
                <div class="px-4 py-2 text-gray-500 text-sm font-bold">ONDERHOUD</div>
                <a href="{{ route('maintenance.index') }}" class="block px-4 py-3 text-gray-700 hover:bg-barroc-yellow hover:text-black transition-colors {{ request()->routeIs('maintenance.*') ? 'bg-barroc-yellow text-black' : '' }}">
                    🔧 Onderhoud
                </a>
                @endif

                <!-- Purchase Links -->
                @if(auth()->user()->department && (auth()->user()->department->name === 'Sales' || auth()->user()->isManager() || auth()->user()->isAdmin()))
                <div class="px-4 py-2 text-gray-500 text-sm font-bold">SALES</div>
                <a href="{{ route('products.index') }}" class="block px-4 py-3 text-gray-700 hover:bg-barroc-yellow hover:text-black transition-colors {{ request()->routeIs('products.*') ? 'bg-barroc-yellow text-black' : '' }}">
                    📊 Inkoop
                </a>
                @endif

                <!-- Admin Links -->
                @if(auth()->user()->isAdmin())
                <div class="px-4 py-2 text-gray-500 text-sm font-bold">BEHEER</div>
                <a href="{{ route('users.index') }}" class="block px-4 py-3 text-gray-700 hover:bg-barroc-yellow hover:text-black transition-colors {{ request()->routeIs('users.*') ? 'bg-barroc-yellow text-black' : '' }}">
                    👤 Gebruikers
                </a>
                @endif

                <!-- Profile Link voor iedereen -->
                <div class="px-4 py-2 text-gray-500 text-sm font-bold">ACCOUNT</div>
                <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-gray-700 hover:bg-barroc-yellow hover:text-black transition-colors {{ request()->routeIs('profile.*') ? 'bg-barroc-yellow text-black' : '' }}">
                    ⚙️ Profiel
                </a>
            </nav>
        </aside>
        @endauth

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('info'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
                    {{ session('info') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>
