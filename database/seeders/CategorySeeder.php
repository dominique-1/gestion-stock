<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Informatique', 'description' => 'Matériel informatique'],
            ['name' => 'Bureau', 'description' => 'Fournitures de bureau'],
            ['name' => 'Logiciel', 'description' => 'Licences et logiciels'],
            ['name' => 'Réseau', 'description' => 'Équipements réseau'],
            ['name' => 'Consommable', 'description' => 'Consommables divers'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], $cat);
        }

        // Sous-catégories
        $info = Category::where('name', 'Informatique')->first();
        Category::firstOrCreate(['name' => 'Ordinateurs portables', 'parent_id' => $info->id]);
        Category::firstOrCreate(['name' => 'Écrans', 'parent_id' => $info->id]);
        Category::firstOrCreate(['name' => 'Claviers & souris', 'parent_id' => $info->id]);
    }
}
