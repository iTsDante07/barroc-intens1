<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Controleer of de departments tabel data heeft
        if (!Schema::hasTable('departments') || Department::count() === 0) {
            $this->command->error('Departments table is empty. Please run DepartmentSeeder first.');
            return;
        }

        // Wis eerst bestaande users
        User::truncate();

        // Haal departments op
        $management = Department::where('name', 'Management')->first();
        $sales = Department::where('name', 'Sales')->first();
        $finance = Department::where('name', 'Finance')->first();
        $maintenance = Department::where('name', 'Maintenance')->first();
        $purchase = Department::where('name', 'Purchase')->first();
        $customerService = Department::where('name', 'Customer Service')->first();

        // Admin gebruiker
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@barrocintens.nl',
            'password' => Hash::make('password'),
            'department_id' => $management->id,
            'role' => 'admin'
        ]);

        // Sales medewerker
        User::create([
            'name' => 'Sales Medewerker',
            'email' => 'sales@barrocintens.nl',
            'password' => Hash::make('password'),
            'department_id' => $sales->id,
            'role' => 'employee'
        ]);

        // Finance medewerker
        User::create([
            'name' => 'Finance Medewerker',
            'email' => 'finance@barrocintens.nl',
            'password' => Hash::make('password'),
            'department_id' => $finance->id,
            'role' => 'employee'
        ]);

        // Monteur
        User::create([
            'name' => 'Monteur',
            'email' => 'maintenance@barrocintens.nl',
            'password' => Hash::make('password'),
            'department_id' => $maintenance->id,
            'role' => 'employee'
        ]);

        // Inkoop medewerker
        User::create([
            'name' => 'Inkoop Medewerker',
            'email' => 'purchase@barrocintens.nl',
            'password' => Hash::make('password'),
            'department_id' => $purchase->id,
            'role' => 'employee'
        ]);

        // Klantenservice
        User::create([
            'name' => 'Klantenservice Medewerker',
            'email' => 'customerservice@barrocintens.nl',
            'password' => Hash::make('password'),
            'department_id' => $customerService->id,
            'role' => 'employee'
        ]);

        $this->command->info('Users seeded successfully!');
    }
}
