<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Installer\Database\Seeders\DatabaseSeeder as BagistoDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Seeder bawaan Bagisto
        $this->call(BagistoDatabaseSeeder::class);

        // Tambahkan custom seeder kamu di bawah sini
        $this->call([
            AddIndonesiaLocaleSeeder::class,
        ]);
    }
}
