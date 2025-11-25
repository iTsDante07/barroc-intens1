<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px] !text-black" style="color: #000 !important;">
        <flux:navlist>
            <flux:navlist.item class="!text-black" style="color: #000 !important;" :href="route('profile.edit')" wire:navigate>{{ __('Profile') }}</flux:navlist.item>
            <flux:navlist.item class="!text-black" style="color: #000 !important;" :href="route('password.edit')" wire:navigate>{{ __('Password') }}</flux:navlist.item>
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <flux:navlist.item class="!text-black" style="color: #000 !important;" :href="route('two-factor.show')" wire:navigate>{{ __('Two-Factor Auth') }}</flux:navlist.item>
            @endif
            <flux:navlist.item class="!text-black" style="color: #000 !important;" :href="route('appearance.edit')" wire:navigate>{{ __('Appearance') }}</flux:navlist.item>
            <flux:navlist.item class="!text-black" style="color: #000 !important;" :href="route('notifications.edit')" wire:navigate>{{ __('Notifications') }}</flux:navlist.item>
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading class="!text-black" style="color: #000 !important;">{{ $heading ?? '' }}</flux:heading>
        <flux:subheading class="!text-black" style="color: #000 !important;">{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full max-w-lg !text-black" style="color: #000 !important;">
            {{ $slot }}
        </div>
    </div>
</div>
