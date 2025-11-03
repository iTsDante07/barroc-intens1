<?php
namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        // Wis eerst de tabel (voor het geval er al data in zit)
        Department::truncate();

        $departments = [
            ['name' => 'Sales'],
            ['name' => 'Finance'],
            ['name' => 'Maintenance'],
            ['name' => 'Purchase'],
            ['name' => 'Management'],
            ['name' => 'Customer Service']
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }

        $this->command->info('Departments seeded successfully!');
    }
}
