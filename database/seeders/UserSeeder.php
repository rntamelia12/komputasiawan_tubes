<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Mengecek apakah email sudah ada, jika belum, insert user baru
        $existingUser = DB::table('users')->where('email', 'admin@ones.id')->first();

        if (!$existingUser) {
            // Jika user belum ada, insert data user baru
            DB::table('users')->insert([
                'name' => 'Administrator',
                'email' => 'admin@ones.id',
                'password' => bcrypt('ones123'),
            ]);
        }
    }
}
