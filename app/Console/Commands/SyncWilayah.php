<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use App\Models\Village;
use App\Models\PostalCode; 

class SyncWilayah extends Command
{
    protected $signature = 'sync:wilayah';
    protected $description = 'Sinkronisasi data wilayah (Provinsi, Kota, Kecamatan) dari SQLite ke database utama.';

    public function handle()
    {
        $this->info('🚀 Memulai sinkronisasi data wilayah dari SQLite...');

        try {
            $this->prepareSqliteConnection();
            $this->truncateTargetTables();

            $this->syncPostalCodes(); 
            
            $this->syncProvinces();
            $this->syncCities();
            $this->syncDistricts();
            $this->syncVillagesAndPostalCodes();
            
            config(['database.connections.sqlite_master' => null]);
            $this->info('✅ Sinkronisasi selesai! Data wilayah siap digunakan.');
            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Gagal sinkronisasi: ' . $e->getMessage());
            return 1;
        }
    }

    protected function prepareSqliteConnection()
    {
        $sqlitePath = base_path('vendor/maftuhichsan/sqlite-wilayah-indonesia/records.sqlite');
        
        if (!file_exists($sqlitePath)) {
            throw new \Exception("File records.sqlite tidak ditemukan. Pastikan paket sudah terinstal.");
        }

        config()->set('database.connections.sqlite_master', [
            'driver' => 'sqlite',
            'database' => $sqlitePath,
            'prefix' => '',
        ]);
    }
    
    protected function truncateTargetTables()
    {
        $this->comment('-> Membersihkan data lama di tabel target...');
        Schema::disableForeignKeyConstraints();
        
        // Urutan Pembersihan Dibalik (Pivot -> Master)
        DB::table('village_postal_codes')->truncate();
        DB::table('district_postal_codes')->truncate();
        DB::table('villages')->truncate();
        DB::table('districts')->truncate();
        DB::table('cities')->truncate();
        DB::table('provinces')->truncate();
        DB::table('postal_codes')->truncate(); 
        
        Schema::enableForeignKeyConstraints();
    }
    
    // =========================================================================
    // BARU: SINKRONISASI POSTAL CODES MASTER
    // =========================================================================
    protected function syncPostalCodes()
    {
        $this->comment('-> Memproses 📮 Kode Pos Master...');
        
        // Ambil semua kode pos unik dari sub_districts dan villages
        $postalCodesFromDistricts = DB::connection('sqlite_master')
            ->table('sub_districts')
            ->whereNotNull('sub_district_postal_codes')
            ->pluck('sub_district_postal_codes');
        
        $postalCodesFromVillages = DB::connection('sqlite_master')
            ->table('villages')
            ->whereNotNull('village_postal_codes')
            ->pluck('village_postal_codes');
        
        // Gabungkan dan extract semua kode pos
        $allPostalCodesRaw = $postalCodesFromDistricts->concat($postalCodesFromVillages);
        
        $uniquePostalCodes = collect();
        
        foreach ($allPostalCodesRaw as $postalCodesStr) {
            if (empty($postalCodesStr)) continue;
            
            $codes = array_map('trim', explode(',', $postalCodesStr));
            foreach ($codes as $code) {
                if (!empty($code)) {
                    $uniquePostalCodes->push($code);
                }
            }
        }
        
        $uniquePostalCodes = $uniquePostalCodes->unique()->values();
        
        $count = 0;
        foreach ($uniquePostalCodes as $code) {
            PostalCode::firstOrCreate(['code' => $code]);
            $count++;
        }
        
        $this->info("   [$count] Kode Pos Master unik berhasil disinkronkan.");
    }
    // =========================================================================


    protected function syncProvinces()
    {
        $sqliteProvinces = DB::connection('sqlite_master')->table('provinces')->get();
        $count = 0;
        $this->comment('-> Memproses 🌍 Provinsi...');
        
        foreach ($sqliteProvinces as $prov) {
            Province::create([
                'province_code' => $prov->province_code,
                'name' => $prov->province_name,
            ]);
            $count++;
        }
        $this->info("   [$count] Provinsi berhasil disinkronkan.");
    }

    protected function syncCities()
    {
        $sqliteCities = DB::connection('sqlite_master')->table('cities')->get();
        $count = 0;
        $this->comment('-> Memproses 🏙️ Kota/Kabupaten...');

        $provinceLookup = Province::pluck('id', 'province_code')->toArray();
        
        foreach ($sqliteCities as $city) {
            $provinceCode = $city->city_province_code;
            $provinceId = $provinceLookup[$provinceCode] ?? null; 
            
            if ($provinceId) {
                City::create([
                    'city_code' => $city->city_code,
                    'province_id' => $provinceId,
                    'name' => $city->city_name,
                    'type' => $city->city_type,
                ]);
                $count++;
            }
        }
        $this->info("   [$count] Kota/Kabupaten berhasil disinkronkan.");
    }

    protected function syncDistricts()
    {
        $sqliteDistricts = DB::connection('sqlite_master')->table('sub_districts')->get();
        $count = 0;
        $postalCodeInserted = 0;
        $this->comment('-> Memproses 🏘️ Kecamatan (Districts) dan Pivot...');

        $cityLookup = City::pluck('id', 'city_code')->toArray();
        $postalCodeLookup = PostalCode::pluck('id', 'code')->toArray();
        
        foreach ($sqliteDistricts as $district) {
            $cityCode = $district->sub_district_city_code;
            $cityId = $cityLookup[$cityCode] ?? null;
            
            if ($cityId) {
                $newDistrict = District::create([
                    'district_code' => $district->sub_district_code,
                    'city_id' => $cityId,
                    'name' => $district->sub_district_name,
                ]);
                
                // ✅ PERBAIKAN: Parse CSV, bukan JSON
                $postalCodesRaw = $district->sub_district_postal_codes;
                
                // Skip jika kosong
                if (empty($postalCodesRaw)) {
                    $count++;
                    continue;
                }
                
                // Split by comma dan trim whitespace
                $postalCodeValues = array_map('trim', explode(',', $postalCodesRaw));
                
                foreach ($postalCodeValues as $postalCodeValue) {
                    // Skip jika kosong
                    if (empty($postalCodeValue)) {
                        continue;
                    }
                    
                    // Cari ID dari postal_codes table
                    $postalCodeId = $postalCodeLookup[$postalCodeValue] ?? null;
                    
                    if ($postalCodeId) {
                        try {
                            DB::table('district_postal_codes')->insert([
                                'district_code' => $newDistrict->district_code,
                                'postal_code_id' => $postalCodeId,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                            $postalCodeInserted++;
                        } catch (\Exception $e) {
                            // Skip duplicate entry
                            if (strpos($e->getMessage(), 'Duplicate entry') === false) {
                                $this->error("❌ Gagal insert postal code {$postalCodeValue} untuk {$newDistrict->name}: " . $e->getMessage());
                            }
                        }
                    } else {
                        $this->warn("⚠️ Kode pos '{$postalCodeValue}' tidak ditemukan di master untuk {$newDistrict->name}");
                    }
                }
                $count++;
            }
        }
        $this->info("   [$count] Kecamatan berhasil disinkronkan.");
        $this->info("   [$postalCodeInserted] Relasi kode pos district berhasil dibuat.");
    }
    
    protected function syncVillagesAndPostalCodes()
    {
        $sqliteVillages = DB::connection('sqlite_master')->table('villages')->get();
        $count = 0;
        $postalCodeInserted = 0;
        $this->comment('-> Memproses 🏡 Desa/Kelurahan (Villages) dan Pivot...');
        
        $districtLookup = District::pluck('id', 'district_code')->toArray();
        $postalCodeLookup = PostalCode::pluck('id', 'code')->toArray();

        foreach ($sqliteVillages as $village) {
            $districtCode = $village->village_sub_district_code;
            $districtId = $districtLookup[$districtCode] ?? null;
            
            if ($districtId) {
                $newVillage = Village::create([
                    'village_code' => $village->village_code,
                    'district_id' => $districtId,
                    'name' => $village->village_name,
                ]);
                
                // ✅ PERBAIKAN: Parse CSV, bukan JSON
                $postalCodesRaw = $village->village_postal_codes;
                
                // Skip jika kosong
                if (empty($postalCodesRaw)) {
                    $count++;
                    continue;
                }
                
                // Split by comma dan trim whitespace
                $postalCodeValues = array_map('trim', explode(',', $postalCodesRaw));
                
                foreach ($postalCodeValues as $postalCodeValue) {
                    // Skip jika kosong
                    if (empty($postalCodeValue)) {
                        continue;
                    }
                    
                    // Cari ID dari postal_codes table
                    $postalCodeId = $postalCodeLookup[$postalCodeValue] ?? null;
                    
                    if ($postalCodeId) {
                        try {
                            DB::table('village_postal_codes')->insert([
                                'village_code' => $newVillage->village_code,
                                'postal_code_id' => $postalCodeId,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                            $postalCodeInserted++;
                        } catch (\Exception $e) {
                            // Skip duplicate entry
                            if (strpos($e->getMessage(), 'Duplicate entry') === false) {
                                $this->error("❌ Gagal insert postal code {$postalCodeValue} untuk {$newVillage->name}: " . $e->getMessage());
                            }
                        }
                    } else {
                        // Ini akan banyak sekali, jadi kita skip warning untuk efisiensi
                        // $this->warn("⚠️ Kode pos '{$postalCodeValue}' tidak ditemukan untuk {$newVillage->name}");
                    }
                }
                $count++;
            }
        }
        $this->info("   [$count] Desa/Kelurahan berhasil disinkronkan.");
        $this->info("   [$postalCodeInserted] Relasi kode pos village berhasil dibuat.");
    }
}