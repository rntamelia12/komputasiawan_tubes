<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar posisi yang akan disisipkan
        $positions = [
            [
                'code' => 'DU',
                'name' => 'Direktur Utama',
                'description' => 'Direktur Utama',
            ],
            [
                'code' => 'SV',
                'name' => 'Supervisor',
                'description' => 'Supervisor',
            ],
            [
                'code' => 'AD',
                'name' => 'Administrasi',
                'description' => 'Administrasi',
            ],
            [
                'code' => 'KM',
                'name' => 'Kepala Marketing',
                'description' => 'Kepala Marketing',
            ],
            [
                'code' => 'TM',
                'name' => 'Tim Marketing',
                'description' => 'Tim Marketing',
            ],
        ];

        // Looping untuk setiap posisi
        foreach ($positions as $position) {
            // Mengecek apakah sudah ada posisi dengan 'code' yang sama
            $existingPosition = DB::table('positions')->where('code', $position['code'])->first();

            if (!$existingPosition) {
                // Jika belum ada, insert data baru
                DB::table('positions')->insert($position);
            }
        }
    }
}
