<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

//         \App\Models\User::factory()->create([
//             'name' => 'Test User',
//             'email' => 'test@example.com',
//         ]);

        User::create([
            'username' => 'DYSEC',
            'password' => Hash::make('Dysec@123'),
            'mobile_number' => '9177385289',
            'role' => 2
        ]);
        User::create(
            [
                'username' => 'CHOS',
                'password' => Hash::make('Chos@123'),
                'mobile_number' => '7569670885',
                'role' => 1
            ]

        );
        User::create(
            [
                'username' => 'SSC_aminity',
                'password' => Hash::make('ssc@123'),
                'mobile_number' => '9398392361',
                'role' => 3
            ]
        );

    }
}
