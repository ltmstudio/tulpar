<?php

namespace Database\Seeders;

use App\Models\User;
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
        User::updateOrCreate(
            ['email' => 'admin@tulpar.kz'],
            [
                'name' => 'Admin',
                'email' => 'admin@tulpar.kz',
                'role' => 'ADM',
                'password' => Hash::make('UzhWb1mJAPAv'),
                'created_at' => now(),
            ]
        );
    }
}
