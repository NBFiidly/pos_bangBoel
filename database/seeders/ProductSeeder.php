<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Panada Ayam Original',
                'price' => 2000.00,
                'stock' => 100,
                'image' => '01KDPNK8MTPN85TY69RY7PK6EV.jpeg',
            ],
            [
                'name' => 'Panada Ayam Pedas',
                'price' => 2000.00,
                'stock' => 100,
                'image' => '01KDPNSC35F31D58Q573QF9GE0.jpeg',
            ],
            [
                'name' => 'Panada IkanPedas',
                'price' => 2000.00,
                'stock' => 100,
                'image' => '01KDPP1XK15V4Y8TMSANNAXAG1.jpg',
            ],
            [
                'name' => 'Panada Bolognese',
                'price' => 2000.00,
                'stock' => 100,
                'image' => '01KDPP4WYQGRHATRBBFJYD88X2.webp',
            ],
            [
                'name' => 'Cireng Ayam Pedas',
                'price' => 2000.00,
                'stock' => 100,
                'image' => '01KDPPM1K48FHA8ECM0GRTGWQV.webp',
            ],
            [
                'name' => 'Cireng Kuah',
                'price' => 15000.00,
                'stock' => 100,
                'image' => '01KDPQ2VDPX7WEGY0KR4Z2AMB0.jpg',
            ],
            [
                'name' => 'Pempek Lenjer',
                'price' => 2000.00,
                'stock' => 100,
                'image' => '01KDPQATES8W99FK947MBJBEW7.webp',
            ],
            [
                'name' => 'Pempek Adaan',
                'price' => 2000.00,
                'stock' => 100,
                'image' => '01KDPQDZ1VK7DJFZPY0P02HAXR.jpg',
            ],
            [
                'name' => 'Pempek Telur',
                'price' => 2000.00,
                'stock' => 100,
                'image' => '01KDPQM7DFCQ5MP8WW3BZXEBWE.jpg',
            ],
            [
                'name' => 'Pempek Kulit',
                'price' => 2000.00,
                'stock' => 100,
                'image' => '01KDPQQX4871N62HCMMSENF851.webp',
            ],
            [
                'name' => 'Risoles Mayo Smoke Beef',
                'price' => 10000.00,
                'stock' => 100,
                'image' => '01KDPQY537P8HSNWHZM9SHHNF8.jpg',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
