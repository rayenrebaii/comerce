<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment(['portable', 'testing'])) {
            throw new \RuntimeException('Les donnees de demonstration sont reservees au mode portable.');
        }

        foreach (['admin' => 1, 'client' => 0] as $name => $role) {
            User::firstOrCreate(['email' => "$name@example.test"], [
                'name' => ucfirst($name).' Demo',
                'password' => Hash::make('Demo2024!'),
                'role' => $role,
            ]);
        }

        $category = Category::firstOrCreate(['nom_categorie' => 'Informatique'], [
            'description' => 'Accessoires de demonstration',
        ]);
        foreach (['Clavier' => 79, 'Souris' => 35, 'Casque' => 120] as $name => $price) {
            Product::firstOrCreate(['product_name' => $name.' Demo'], [
                'product_description' => 'Produit de demonstration pour tester la boutique.',
                'product_price' => $price,
                'quantity_available' => 25,
                'product_img' => '/assets/demo-product.svg',
                'discount' => 5,
                'category_id' => $category->id,
            ]);
        }
    }
}
