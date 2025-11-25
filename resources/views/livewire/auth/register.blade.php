<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        Session::regenerate();

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Account aanmaken')" :description="__('Voer hieronder uw gegevens in om uw account aan te maken')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <flux:input
            wire:model="name"
            :label="__('Naam')"
            type="text"
            required
            autofocus
            autocomplete="name"
            :placeholder="__('Volledige naam')"
            class="focus:ring-yellow-400"
        />

        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('E-mailadres')"
            type="email"
            required
            autocomplete="email"
            placeholder="email@example.com"
            class="focus:ring-yellow-400"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('Wachtwoord')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Wachtwoord')"
            viewable
            class="focus:ring-yellow-400"
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Bevestig wachtwoord')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Bevestig wachtwoord')"
            viewable
            class="focus:ring-yellow-400"
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full text-grey-100 !bg-yellow-400" data-test="register-user-button">
                {{ __('Account aanmaken') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        <span>{{ __('Heeft u al een account?') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('Inloggen') }}</flux:link>
    </div>
</div>
