<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::insert(
            [
                'name' => 'Kasir 1',
                'email' => 'kasir1@email.com',
                'password' => bcrypt('kasir1234'), // Ensure to hash the password
            ],
            [
                'name' => 'Kasir 2',
                'email' => 'kasir2@email.com',
                'password' => bcrypt('kasir2341'), // Ensure to hash the password
            ],
            [
                'name' => 'Kasir 3',
                'email' => 'kasir3@email.com',
                'password' => bcrypt('kasir3412'), // Ensure to hash the password
            ],
            [
                'name' => 'Kasir 4',
                'email' => 'kasir4@email.com',
                'password' => bcrypt('kasir4123'), // Ensure to hash the password
            ]
        );
    }
}
