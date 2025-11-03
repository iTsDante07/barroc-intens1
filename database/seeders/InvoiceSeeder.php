<?php
namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceSeeder extends Seeder
{
    public function run()
    {
        // Wis eerst bestaande facturen
        DB::table('invoice_items')->delete();
        DB::table('invoices')->delete();

        // Haal een BKR goedgekeurde klant op
        $customer = Customer::where('bkr_approved', true)->first();
        $user = User::first();

        if (!$customer) {
            $this->command->info('Geen BKR goedgekeurde klant gevonden. Maak eerst een klant aan met BKR goedkeuring.');
            return;
        }

        if (!$user) {
            $this->command->info('Geen gebruiker gevonden.');
            return;
        }

        // Maak test facturen aan
        $invoices = [
            [
                'customer_id' => $customer->id,
                'user_id' => $user->id,
                'invoice_number' => 'F2024-0001',
                'invoice_date' => now()->subDays(30),
                'due_date' => now()->subDays(10),
                'subtotal' => 2999.99,
                'vat_amount' => 629.99,
                'total_amount' => 3629.98,
                'status' => 'overdue',
                'notes' => 'Eerste factuur voor Barroc Intens Espresso Machine',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'customer_id' => $customer->id,
                'user_id' => $user->id,
                'invoice_number' => 'F2024-0002',
                'invoice_date' => now()->subDays(15),
                'due_date' => now()->addDays(15),
                'subtotal' => 4599.99,
                'vat_amount' => 965.99,
                'total_amount' => 5565.98,
                'status' => 'verzonden',
                'notes' => 'Factuur voor Barroc Intens Pro machine',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'customer_id' => $customer->id,
                'user_id' => $user->id,
                'invoice_number' => 'F2024-0003',
                'invoice_date' => now()->subDays(5),
                'due_date' => now()->addDays(25),
                'subtotal' => 1999.99,
                'vat_amount' => 419.99,
                'total_amount' => 2419.98,
                'status' => 'betaald',
                'notes' => 'Factuur voor Barroc Intens Compact - reeds betaald',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        // Insert invoices
        foreach ($invoices as $invoiceData) {
            $invoiceId = DB::table('invoices')->insertGetId($invoiceData);

            // Voeg invoice items toe
            $items = [];
            if ($invoiceData['invoice_number'] === 'F2024-0001') {
                $items[] = [
                    'invoice_id' => $invoiceId,
                    'description' => 'Barroc Intens Espresso Machine',
                    'quantity' => 1,
                    'unit_price' => 2999.99,
                    'total_price' => 2999.99,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            } elseif ($invoiceData['invoice_number'] === 'F2024-0002') {
                $items[] = [
                    'invoice_id' => $invoiceId,
                    'description' => 'Barroc Intens Pro Machine',
                    'quantity' => 1,
                    'unit_price' => 4599.99,
                    'total_price' => 4599.99,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            } else {
                $items[] = [
                    'invoice_id' => $invoiceId,
                    'description' => 'Barroc Intens Compact Machine',
                    'quantity' => 1,
                    'unit_price' => 1999.99,
                    'total_price' => 1999.99,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            DB::table('invoice_items')->insert($items);
        }

        $this->command->info(count($invoices) . ' test facturen succesvol aangemaakt!');
    }
}
