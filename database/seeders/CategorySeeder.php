<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $items = [
            ['nama' => 'sapi', 'deskripsi' => 'Untuk hewan sapi'],
            ['nama' => 'pakan', 'deskripsi' => 'Untuk pakan sapi'],
            ['nama' => 'peralatan', 'deskripsi' => 'Untuk peralatan sapi'],
        ];

        foreach ($items as $item) {
            DB::table('categories')->updateOrInsert(
                ['nama' => $item['nama']],
                array_merge($item, ['created_at' => $now, 'updated_at' => $now])
            );
        }
    }
}
