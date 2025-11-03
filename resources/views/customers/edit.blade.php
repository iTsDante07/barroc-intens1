@extends('components.layouts.app')

@section('title', $customer->company_name . ' Bewerken - Barroc Intens')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">{{ $customer->company_name }} Bewerken</h1>
    <p class="text-gray-600">Wijzig de klantgegevens</p>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('customers.update', $customer) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Bedrijfsnaam -->
            <div class="md:col-span-2">
                <label for="company_name" class="block text-sm font-medium text-gray-700">Bedrijfsnaam *</label>
                <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $customer->company_name) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">
            </div>

            <!-- Contactpersoon -->
            <div>
                <label for="contact_name" class="block text-sm font-medium text-gray-700">Contactpersoon *</label>
                <input type="text" name="contact_name" id="contact_name" value="{{ old('contact_name', $customer->contact_name) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">E-mail *</label>
                <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">
            </div>

            <!-- Telefoon -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Telefoon *</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $customer->phone) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">
            </div>

            <!-- Adres -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">Adres *</label>
                <input type="text" name="address" id="address" value="{{ old('address', $customer->address) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">
            </div>

            <!-- Stad -->
            <div>
                <label for="city" class="block text-sm font-medium text-gray-700">Stad *</label>
                <input type="text" name="city" id="city" value="{{ old('city', $customer->city) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">
            </div>

            <!-- Postcode -->
            <div>
                <label for="postal_code" class="block text-sm font-medium text-gray-700">Postcode *</label>
                <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $customer->postal_code) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50">
            </div>
        </div>

        <!-- Buttons -->
        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('customers.show', $customer) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
                Annuleren
            </a>
            <button type="submit" class="bg-yellow-500 text-black px-6 py-2 rounded-lg font-semibold hover:bg-yellow-600 transition-colors">
                Klant Bijwerken
            </button>
        </div>
    </form>
</div>
@endsection 
