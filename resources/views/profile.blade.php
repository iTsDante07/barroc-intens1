@extends('components.layouts.app')

@section('title', 'Profiel - Barroc Intens')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Profiel Instellingen</h1>
        <p class="text-gray-600">Beheer je accountgegevens en voorkeuren</p>
    </div>
    <div class="flex space-x-4">
        <a href="{{ route('notifications.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
            Notificaties
        </a>
        <a href="{{ route('dashboard') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
            Terug naar Dashboard
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Profiel Informatie -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Profiel Informatie</h2>

        <form wire:submit="updateProfileInformation" class="space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Naam</label>
                <input type="text"
                       id="name"
                       wire:model="name"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       required
                       autofocus
                       autocomplete="name">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">E-mailadres</label>
                <input type="email"
                       id="email"
                       wire:model="email"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       required
                       autocomplete="email">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
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
                            <p class="mt-2">
                                <button type="button"
                                        wire:click="sendVerificationNotification"
                                        class="text-sm font-medium text-yellow-700 hover:text-yellow-600 underline">
                                    {{ __('Klik hier om de verificatie e-mail opnieuw te versturen.') }}
                                </button>
                            </p>
                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 text-sm font-medium text-green-600">
                                    {{ __('Een nieuwe verificatielink is naar je e-mailadres verstuurd.') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex items-center justify-between pt-4">
                <div>
                    @if (session()->has('message'))
                        <div class="text-green-600 font-medium">
                            {{ session('message') }}
                        </div>
                    @endif
                </div>

                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('Opslaan') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Account Informatie -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Account Informatie</h2>

        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-600">Rol</label>
                <p class="mt-1 text-lg text-gray-900 capitalize">
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

<!-- Account Acties -->
<div class="mt-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Account Verwijderen</h2>
        <p class="text-gray-600 mb-4">Verwijder permanent je account en alle bijbehorende gegevens.</p>

        @if (view()->exists('livewire.settings.delete-user-form'))
            <livewire:settings.delete-user-form />
        @else
            <div class="bg-red-50 border border-red-200 rounded-md p-4">
                <p class="text-red-700">Delete user form component niet beschikbaar.</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('profile-updated', (event) => {
            // Toon een success message
            const messageDiv = document.createElement('div');
            messageDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            messageDiv.textContent = 'Profiel succesvol bijgewerkt!';
            document.body.appendChild(messageDiv);

            setTimeout(() => {
                messageDiv.remove();
            }, 3000);
        });
    });
</script>
@endpush
@endsection
