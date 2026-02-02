<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $catInfo = Category::where('name', 'Informatique')->first();
        $catBureau = Category::where('name', 'Bureau')->first();
        $catLogiciel = Category::where('name', 'Logiciel')->first();

        $products = [
            [
                'name' => 'Laptop Pro 15"',
                'barcode' => 'LP001',
                'supplier' => 'TechSupplier',
                'price' => 1299.99,
                'category_id' => $catInfo->id,
                'stock_min' => 2,
                'stock_optimal' => 10,
                'current_stock' => 7,
                'description' => 'Ordinateur portable haute performance',
            ],
            [
                'name' => 'Moniteur 27" 4K',
                'barcode' => 'MN001',
                'supplier' => 'DisplayCo',
                'price' => 349.99,
                'category_id' => $catInfo->id,
                'stock_min' => 3,
                'stock_optimal' => 15,
                'current_stock' => 12,
                'description' => 'Moniteur 4K UHD',
            ],
            [
                'name' => 'Pack de 10 Stylos',
                'barcode' => 'ST001',
                'supplier' => 'OfficeSupply',
                'price' => 4.99,
                'category_id' => $catBureau->id,
                'stock_min' => $faker->numberBetween(0, 5),
                'stock_optimal' => $faker->numberBetween(2, 20),
                'current_stock' => $faker->numberBetween(0, 100),
                'description' => 'Stylos bleus',
            ],
            [
                'name' => 'Licence Suite Office',
                'barcode' => 'SW001',
                'supplier' => 'SoftCorp',
                'price' => 199.99,
                'category_id' => $catLogiciel->id,
                'stock_min' => 1,
                'stock_optimal' => 5,
                'current_stock' => 3,
                'description' => 'Licence annuelle',
            ],
        ];

        foreach ($products as $p) {
            Product::firstOrCreate(['barcode' => $p['barcode']], $p);
        }
    }
}
