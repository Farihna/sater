<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $faker = Faker::create('id_ID'); 

        for ($i = 1; $i <= 1; $i++) {
            $harga = $faker->numberBetween(15, 60) * 1000000; // Harga antara 15 juta sampai 60 juta

            $productId = DB::table('product')->insertGetId([
                'category_id' => 1,
                'nama' => 'Sapi Limousin Jumbo',
                'deskripsi' => $faker->paragraph(3),
                'harga' => $harga,
                'image_url' => 'sapi-contoh-' . $faker->numberBetween(1, 5) . '.jpg', 
                'stok' => rand(1, 10),

                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('detail_sapi')->insert([
                'product_id' => $productId,
                'berat' => rand(300, 500),
                'usia' => rand(1, 5) . ' tahun',
                'gender' => rand(0, 1) ? 'jantan' : 'betina',
                'sertifikat_kesehatan' => 'SK-' . strtoupper(Str::random(6)),

                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        
         // --- Kategori Pakan ---
        for ($i = 1; $i <= 1; $i++) {
            $productId = DB::table('product')->insertGetId([
                'category_id' => 2,
                'nama' => "Pakan Sapi Premium $i",
                'deskripsi' => "Pakan sapi bergizi tinggi untuk pertumbuhan optimal.",
                'harga' => rand(100000, 300000),
                'image_url' => 'pakan-contoh-' . $faker->numberBetween(1, 5) . '.jpg', // Asumsi ada 5 gambar sapi contoh
                'stok' => rand(10, 50),

                'created_at' => $now,
                'updated_at' => $now,   
            ]);

            DB::table('detail_pakan')->insert([
                'product_id' => $productId,
                'berat' => rand(20, 50),
                'jenis_pakan' => ['Konsentrat', 'Hijauan', 'Fermentasi'][array_rand(['Konsentrat', 'Hijauan', 'Fermentasi'])],
                
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // --- Kategori Peralatan ---
        for ($i = 1; $i <= 1; $i++) {
            $productId = DB::table('product')->insertGetId([
                'category_id' => 3,
                'nama' => "Peralatan Peternak $i",
                'deskripsi' => "Peralatan penting untuk memelihara sapi dengan efisien.",
                'harga' => rand(50000, 300000),
                'image_url' => 'peralatan-contoh-' . $faker->numberBetween(1, 5) . '.jpg', // Asumsi ada 5 gambar sapi contoh
                'stok' => rand(5, 20),
                
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
