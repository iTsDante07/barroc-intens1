<?php
namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Wis eerst bestaande producten
        Product::truncate();

        $products = [
            [
                'name' => 'Barroc Intens Espresso Machine',
                'description' => 'Professionele espresso machine voor horecagelegenheden met dubbele boiler en geïntegreerde melkschuimer.',
                'price' => 2999.99,
                'stock' => 5
            ],
            [
                'name' => 'Barroc Intens Pro',
                'description' => 'Geavanceerde koffiemachine met dubbele boiler, touchscreen interface en geautomatiseerde reiniging.',
                'price' => 4599.99,
                'stock' => 3
            ],
            [
                'name' => 'Barroc Intens Compact',
                'description' => 'Compacte machine voor kleinere horecagelegenheden met professionele kwaliteit in klein formaat.',
                'price' => 1999.99,
                'stock' => 8
            ],
            [
                'name' => 'Barroc Intens Premium Beans',
                'description' => 'Premium koffiebonen direct geïmporteerd uit Italië. Donker gebrand voor de perfecte espresso.',
                'price' => 24.99,
                'stock' => 50
            ],
            [
                'name' => 'Barroc Intens Under Counter',
                'description' => 'Inbouw koffiemachine voor onder de counter. Perfect voor bars en restaurants met beperkte ruimte.',
                'price' => 3499.99,
                'stock' => 2
            ],
            [
                'name' => 'Barroc Intens Milk Cooler',
                'description' => 'Professionele melkkoeler unit voor perfecte melktextuur en temperatuur.',
                'price' => 899.99,
                'stock' => 6
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('Products seeded successfully!');
    }
}
