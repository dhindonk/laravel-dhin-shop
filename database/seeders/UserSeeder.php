<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(3)->create();
        User::factory()->create([
            'name' => 'Admin Gudang',
            'email' => 'dhinshop@gmail.com',
            'password' => Hash::make('121212'),
            'phone' => '08871923885',
            'roles' => 'ADMIN',
        ]);
    }
}
