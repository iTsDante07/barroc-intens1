<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            DepartmentSeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
            CustomerSeeder::class,
            InvoiceSeeder::class, 
        ]);
    }
}
