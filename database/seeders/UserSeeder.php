<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
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
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'phone_number' => '081234567890',
            'email' => 'admin@rs-indriati.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'), // Default password: 12345678
            'remember_token' => Str::random(10),
            'is_admin' => true
        ]);

        // Create some regular users if needed
        $userCount = env('USER_SEED_COUNT', 5);
        
        $faker = app(\Faker\Generator::class);
        
        for ($i = 0; $i < $userCount; $i++) {
            User::create([
                'name' => $faker->name,
                'phone_number' => $faker->numerify('08##########'),
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // Default password: password
                'remember_token' => Str::random(10),
                'is_admin' => false
            ]);
        }
    }
}