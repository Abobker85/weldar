<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Company;
use App\Models\Welder;
use App\Models\QualificationTest;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);
        
        // Call the seeders which will create companies, welders, and qualification tests
        $this->call([
            QualificationTestSeeder::class,
            TestQualificationSeeder::class,
        ]);
    }
}
