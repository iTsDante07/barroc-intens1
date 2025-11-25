<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<section class="w-full text-black">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Notifications')" :subheading=" __('Update the notification settings for your account')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="enabled">
                {{ __('Enabled') }}
            </flux:radio>
            <flux:radio value="disabled">
                {{ __('Disabled') }}
            </flux:radio>
        </flux:radio.group>
    </x-settings.layout>
</section>
