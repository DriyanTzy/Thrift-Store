<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin User (Conductor Control Center)
        $admin = User::updateOrCreate(
            ['email' => 'conductor@ruanglemari.com'],
            [
                'name' => 'Conductor Commander',
                'password' => Hash::make('takutyadek'),
                'player_tag' => 'conductor',
                'is_admin' => true,
            ]
        );

        // 2. Create Demo User
        $user = User::create([
            'name' => 'Shaw Nouzen',
            'email' => 'andriyanmaulana49@gmail.com',
            'password' => Hash::make('password123'),
            'player_tag' => 'andriyanmaulana49@gmail.com',
            'is_admin' => false,
        ]);

        // 3. Create Thrifting Categories
        $jaket = Category::create([
            'name' => 'Outerwear & Jaket',
            'slug' => 'outerwear-jaket',
            'banner_color' => 'linear-gradient(135deg, #0F172A 0%, #1E293B 100%)',
            'icon' => 'fa-vest',
        ]);

        $hoodie = Category::create([
            'name' => 'Hoodie & Crewneck',
            'slug' => 'hoodie-crewneck',
            'banner_color' => 'linear-gradient(135deg, #334155 0%, #1E293B 100%)',
            'icon' => 'fa-hoodie',
        ]);

        $kaos = Category::create([
            'name' => 'Kaos & Vintage Tee',
            'slug' => 'kaos-vintage-tee',
            'banner_color' => 'linear-gradient(135deg, #B45309 0%, #78350F 100%)',
            'icon' => 'fa-shirt',
        ]);

        $celana = Category::create([
            'name' => 'Celana & Denim',
            'slug' => 'celana-denim',
            'banner_color' => 'linear-gradient(135deg, #1D4ED8 0%, #1E40AF 100%)',
            'icon' => 'fa-scissors',
        ]);

        $sepatu = Category::create([
            'name' => 'Sepatu & Sneakers',
            'slug' => 'sepatu-sneakers',
            'banner_color' => 'linear-gradient(135deg, #047857 0%, #065F46 100%)',
            'icon' => 'fa-shoe-prints',
        ]);

        $aksesoris = Category::create([
            'name' => 'Topi & Aksesoris',
            'slug' => 'topi-aksesoris',
            'banner_color' => 'linear-gradient(135deg, #6D28D9 0%, #4C1D95 100%)',
            'icon' => 'fa-hat-cowboy',
        ]);

        // 4. Create Thrifting Products
        $products = [
            // Outerwear & Jaket
            [
                'category_id' => $jaket->id,
                'name' => 'Nike Vintage Windbreaker 90s Block Color',
                'slug' => 'nike-vintage-windbreaker-90s',
                'description' => 'Bahan parasut vintage 90s pekat warna navy/gold, zipper lancar YKK, karet lengan kencang. PxL: 68x58 cm. Kondisi 9.5/10 Like New.',
                'price' => 185000.00,
                'stock' => 1,
                'badge' => '9.5/10 - SIZE L',
                'is_active' => true,
            ],
            [
                'category_id' => $jaket->id,
                'name' => 'Carhartt Detroit Jacket Duck Canvas Brown',
                'slug' => 'carhartt-detroit-jacket-duck-canvas',
                'description' => 'Heavy duck canvas vintage fade alami ganteng, blanket lining hangat, kerah corduroy cokelat. PxL: 72x62 cm. Garansi Authentic.',
                'price' => 450000.00,
                'stock' => 1,
                'badge' => 'VINTAGE - SIZE XL',
                'is_active' => true,
            ],
            // Hoodie & Crewneck
            [
                'category_id' => $hoodie->id,
                'name' => 'Champion Reverse Weave Heavyweight Hoodie Navy',
                'slug' => 'champion-reverse-weave-navy',
                'description' => 'Bahan tebal terry cotton khas Reverse Weave, C logo patch lengan kencang. PxL: 66x54 cm. Kondisi mulus 9/10.',
                'price' => 275000.00,
                'stock' => 1,
                'badge' => '9/10 - SIZE M',
                'is_active' => true,
            ],
            [
                'category_id' => $hoodie->id,
                'name' => 'Stussy Stock Logo Vintage Crewneck Black',
                'slug' => 'stussy-stock-logo-crewneck-black',
                'description' => 'Bordir font stussy dada rapih, Made in USA vintage tag, kain tidak berbulu. PxL: 70x56 cm.',
                'price' => 320000.00,
                'stock' => 1,
                'badge' => 'RARE - SIZE L',
                'is_active' => true,
            ],
            // Kaos & Vintage Tee
            [
                'category_id' => $kaos->id,
                'name' => 'Vintage Harley Davidson 3D Emblem Tee 1994',
                'slug' => 'vintage-harley-davidson-3d-emblem-1994',
                'description' => 'Jahitan single stitch atas bawah, grafis 3D Emblem legendaris, washed fade alami sangat eksklusif. PxL: 71x57 cm.',
                'price' => 380000.00,
                'stock' => 1,
                'badge' => 'COLLECTOR - SIZE L',
                'is_active' => true,
            ],
            [
                'category_id' => $kaos->id,
                'name' => 'Supreme Box Logo Tee Classic White Red 2018',
                'slug' => 'supreme-box-logo-tee-white',
                'description' => 'Tee Supreme ikonik dengan Box Logo sablon kencang no crack, tag hologram terverifikasi. PxL: 69x52 cm.',
                'price' => 420000.00,
                'stock' => 1,
                'badge' => 'AUTHENTIC - SIZE M',
                'is_active' => true,
            ],
            // Celana & Denim
            [
                'category_id' => $celana->id,
                'name' => "Levi's 501 Original Straight Cut Denim Vintage Blue",
                'slug' => 'levis-501-original-straight-cut-blue',
                'description' => 'Jeans vintage wash biru classic, button fly, selvedge detail, konds mulus no kikis hem. Waist 82 cm (Size 32).',
                'price' => 295000.00,
                'stock' => 1,
                'badge' => 'SIZE 32 - VERY GOOD',
                'is_active' => true,
            ],
            [
                'category_id' => $celana->id,
                'name' => 'Dickies 874 Original Fit Work Pants Khaki',
                'slug' => 'dickies-874-original-fit-khaki',
                'description' => 'Bahan kaku khas 874 work pants, warna khaki mulus 95%, lipatan tengah kencang. Waist 86 cm (Size 34).',
                'price' => 210000.00,
                'stock' => 1,
                'badge' => 'SIZE 34 - 9.5/10',
                'is_active' => true,
            ],
            // Sepatu & Sneakers
            [
                'category_id' => $sepatu->id,
                'name' => 'Adidas Samba OG White Black Gum Sole',
                'slug' => 'adidas-samba-og-white-black-gum',
                'description' => 'Upper kulit sintetis & suede mulus bersih, insole empuk ori, outsole gum grip kencang. Replacement box included.',
                'price' => 550000.00,
                'stock' => 1,
                'badge' => 'SIZE 42 - LIKE NEW',
                'is_active' => true,
            ],
            // Topi & Aksesoris
            [
                'category_id' => $aksesoris->id,
                'name' => 'Stussy Stock Cuff Beanie Hat Black',
                'slug' => 'stussy-stock-cuff-beanie-black',
                'description' => 'Beanie rajut kencang tidak melar, logo bordir stussy presisi, bahan hangat 100% acrylic.',
                'price' => 120000.00,
                'stock' => 1,
                'badge' => 'GOOD CONDITION',
                'is_active' => true,
            ],
        ];

        foreach ($products as $prodData) {
            $prodData['user_id'] = $user->id;
            Product::create($prodData);
        }

        // 5. Seed Demo Order with Receipt
        $sampleProduct = Product::where('slug', 'nike-vintage-windbreaker-90s')->first();
        if ($sampleProduct) {
            $order = Order::create([
                'order_number' => 'THRIFT-20261005-7D2D8D',
                'user_id' => $user->id,
                'player_tag' => 'andriyanmaulana49@gmail.com',
                'total_price' => $sampleProduct->price,
                'payment_method' => 'qris',
                'payment_status' => 'paid',
                'order_status' => 'completed',
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $sampleProduct->id,
                'product_name' => $sampleProduct->name,
                'price' => $sampleProduct->price,
                'quantity' => 1,
                'subtotal' => $sampleProduct->price,
            ]);
        }
    }
}
