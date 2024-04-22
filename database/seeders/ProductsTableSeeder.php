<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Products;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Books',
                'description' => 'Description for Product',
                'price' => 10.99,
                'product_code' => 'ABC123',
                'product_status' => 1,
                'created_by' => 2
            ],
            [
                'name' => 'Shoe',
                'description' => 'Description for Product',
                'price' => 11.99,
                'product_code' => 'DEF456',
                'product_status' => 1,
                'created_by' =>1
            ],
            [
                'name' => 'Watch',
                'description' => 'Description for Product',
                'price' => 19.99,
                'product_code' => 'AS4332',
                'product_status' => 1,
                'created_by' =>1
            ],
            [
                'name' => 'Herbolene',
                'description' => 'Description for Product',
                'price' => 18.99,
                'product_code' => 'HHSJ668',
                'product_status' => 1,
                'created_by' => 2
            ],
            [
                'name' => 'Vera',
                'description' => 'Description for Product',
                'price' => 16.99,
                'product_code' => 'DSEA23',
                'product_status' => 1,
                'created_by' => 2
            ],
           
        ];
        foreach ($products as $product) {
            Products::create($product);
        }
    }
}
