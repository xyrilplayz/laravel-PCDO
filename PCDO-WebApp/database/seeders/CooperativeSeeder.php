<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Cooperative;

class CooperativeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cooperative::create([
            'id' => sprintf('%03d-%03d', rand(100, 999), rand(100, 999)),
            'name' => 'Primary Coop 1',
            'type' => 'primary',
            'holder' => null,
        ]);
    }
}
