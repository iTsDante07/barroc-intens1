<?php
namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        // Wis eerst bestaande klanten
        DB::table('customers')->delete();

        $customers = [
            [
                'company_name' => 'Café de Hoek',
                'contact_name' => 'Jan Jansen',
                'email' => 'jan@cafedehoek.nl',
                'phone' => '020-1234567',
                'address' => 'Hoekstraat 123',
                'city' => 'Amsterdam',
                'postal_code' => '1234 AB',
                'bkr_checked' => false,
                'bkr_approved' => false,
                'bkr_notes' => null,
            ],
            [
                'company_name' => 'Restaurant Italia',
                'contact_name' => 'Maria Rossi',
                'email' => 'maria@restaurantitalia.nl',
                'phone' => '020-7654321',
                'address' => 'Pizzalaan 456',
                'city' => 'Amsterdam',
                'postal_code' => '5678 CD',
                'bkr_checked' => true,
                'bkr_approved' => true,
                'bkr_notes' => 'BKR check uitgevoerd op 21-10-2024 - GOEDGEKEURD',
            ],
            [
                'company_name' => 'Hotel Grand',
                'contact_name' => 'Peter van Dijk',
                'email' => 'peter@hotelgrand.nl',
                'phone' => '020-5556677',
                'address' => 'Grandstraat 789',
                'city' => 'Amsterdam',
                'postal_code' => '9012 EF',
                'bkr_checked' => true,
                'bkr_approved' => false,
                'bkr_notes' => 'BKR check uitgevoerd op 21-10-2024 - AFGEKEURD: Betalingsachterstand',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        $this->command->info(count($customers) . ' klanten succesvol toegevoegd!');
    }
}
