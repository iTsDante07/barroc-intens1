@extends('components.layouts.app')

@section('title', 'Profiel - Barroc Intens')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Profiel Instellingen</h1>
        <p class="text-gray-600">Beheer je accountgegevens en voorkeuren</p>
    </div>
    <div class="flex space-x-4">
        <a href="{{ route('dashboard') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
            Terug naar Dashboard
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Profiel Informatie Formulier -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Persoonlijke Gegevens</h2>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Naam</label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name', auth()->user()->name) }}"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       required
                       autofocus>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">E-mailadres</label>
                <input type="email"
                       id="email"
                       name="email"
                       value="{{ old('email', auth()->user()->email) }}"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       required>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @if (session('status') === 'profile-updated')
                <div class="bg-green-50 border border-green-200 rounded-md p-4">
                    <p class="text-sm text-green-600">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Profiel succesvol bijgewerkt!
                    </p>
                </div>
            @endif

            <div class="pt-4">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Opslaan
                </button>
            </div>
        </form>

        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            {{ __('Je e-mailadres is niet geverifieerd.') }}
                        </p>
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-yellow-700 hover:text-yellow-600 underline mt-1">
                                {{ __('Klik hier om de verificatie e-mail opnieuw te versturen.') }}
                            </button>
                        </form>
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-sm font-medium text-green-600">
                                {{ __('Een nieuwe verificatielink is naar je e-mailadres verstuurd.') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Wachtwoord Wijzigen -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Wachtwoord Wijzigen</h2>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700">Huidig wachtwoord</label>
                <input type="password"
                       id="current_password"
                       name="current_password"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       required>
                @error('current_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Nieuw wachtwoord</label>
                <input type="password"
                       id="password"
                       name="password"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       required>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Bevestig nieuw wachtwoord</label>
                <input type="password"
                       id="password_confirmation"
                       name="password_confirmation"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       required>
            </div>

            @if (session('status') === 'password-updated')
                <div class="bg-green-50 border border-green-200 rounded-md p-4">
                    <p class="text-sm text-green-600">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Wachtwoord succesvol gewijzigd!
                    </p>
                </div>
            @endif

            <div class="pt-4">
                <button type="submit"
                        class="bg-green-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-700 transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    Wachtwoord wijzigen
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Account Informatie (alleen-lezen) -->
<div class="mt-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Account Informatie</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-600">Rol</label>
                <p class="mt-1 text-gray-900 capitalize">
                    {{ auth()->user()->role ?? 'Gebruiker' }}
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Afdeling</label>
                <p class="mt-1 text-gray-700">
                    {{ auth()->user()->department->name ?? 'Geen afdeling toegewezen' }}
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Account aangemaakt op</label>
                <p class="mt-1 text-gray-700">
                    {{ auth()->user()->created_at->format('d-m-Y H:i') }}
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">E-mail geverifieerd</label>
                <p class="mt-1">
                    @if(auth()->user()->hasVerifiedEmail())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Ja
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Nee
                        </span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Account Verwijderen -->
<div class="mt-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Account Verwijderen</h2>
        <p class="text-gray-600 mb-4">
            Zodra je account is verwijderd, worden al je resources en gegevens permanent gewist.
            Voordat je je account verwijdert, download alle gegevens of informatie die je wilt behouden.
        </p>

        <button
            type="button"
            onclick="document.getElementById('delete-user-modal').classList.remove('hidden')"
            class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition-colors"
        >
            Account Verwijderen
        </button>
    </div>
</div>

<!-- Delete User Modal -->
<div id="delete-user-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900">Account verwijderen bevestigen</h3>

            <div class="mt-2">
                <p class="text-sm text-gray-500">
                    Weet je zeker dat je je account wilt verwijderen? Deze actie kan niet ongedaan worden gemaakt.
                </p>

                <form method="POST" action="{{ route('profile.destroy') }}" class="mt-4">
                    @csrf
                    @method('DELETE')

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Voer je wachtwoord in om te bevestigen:
                        </label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button
                            type="button"
                            onclick="document.getElementById('delete-user-modal').classList.add('hidden')"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-400 transition-colors"
                        >
                            Annuleren
                        </button>

                        <button
                            type="submit"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition-colors"
                        >
                            Account Verwijderen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Sluit modal bij klik buiten modal
    const modal = document.getElementById('delete-user-modal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    }

    // Sluit modal bij escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.getElementById('delete-user-modal').classList.add('hidden');
        }
    });

    // Toon success messages
    @if(session('status') === 'profile-updated' || session('status') === 'password-updated')
        setTimeout(() => {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            messageDiv.textContent = '{{ session("status") === "profile-updated" ? "Profiel succesvol bijgewerkt!" : "Wachtwoord succesvol gewijzigd!" }}';
            document.body.appendChild(messageDiv);

            setTimeout(() => {
                messageDiv.remove();
            }, 3000);
        }, 100);
    @endif
</script>
@endpush
@endsection
