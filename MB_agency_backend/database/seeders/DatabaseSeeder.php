<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        Storage::deleteDirectory('File');
        Storage::makeDirectory('File');
        $this->call(RoleSeeder::class);
        User::create([
            'name' => 'name',
            'email' => 'email@gmail.com',
            'password' => Hash::make('123456'),
            'role_id' => 1
        ]);
    }
}
