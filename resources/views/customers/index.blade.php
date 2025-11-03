@extends('components.layouts.app')

@section('title', 'Klanten - Barroc Intens')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Klanten</h1>
        <p class="text-gray-600">Beheer alle klanten</p>
    </div>
    <a href="{{ route('customers.create') }}" class="bg-yellow-500 text-black px-6 py-3 rounded-lg font-semibold hover:bg-yellow-600 transition-colors">
        Nieuwe Klant
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Klanten Overzicht</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bedrijfsnaam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telefoon</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BKR Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acties</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($customers as $customer)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $customer->company_name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $customer->contact_name }}</div>
                        <div class="text-sm text-gray-500">{{ $customer->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $customer->phone }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($customer->bkr_checked)
                            @if($customer->bkr_approved)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Goedgekeurd
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Afgekeurd
                                </span>
                            @endif
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Niet gecontroleerd
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <a href="{{ route('customers.show', $customer) }}" class="text-blue-600 hover:text-blue-900">Bekijken</a>
                        <a href="{{ route('customers.edit', $customer) }}" class="text-green-600 hover:text-green-900">Bewerken</a>
                        @if(!$customer->bkr_checked)
                            <form action="{{ route('customers.check-bkr', $customer) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-yellow-600 hover:text-yellow-900">BKR Check</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
