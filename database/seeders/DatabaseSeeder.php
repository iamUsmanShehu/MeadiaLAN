<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\AdminUser;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin user
        AdminUser::firstOrCreate(
            ['username' => 'admin'],
            [
                'email' => 'admin@medialan.local',
                'password' => Hash::make('admin123'),
                'is_active' => true,
            ]
        );

        // Create default categories
        $categories = [
            ['name' => 'Action', 'description' => 'High-octane action-packed films'],
            ['name' => 'Drama', 'description' => 'Compelling dramatic stories'],
            ['name' => 'Comedy', 'description' => 'Laugh out loud comedies'],
            ['name' => 'Thriller', 'description' => 'Suspenseful thrillers'],
            ['name' => 'Horror', 'description' => 'Scary and spooky films'],
            ['name' => 'Romance', 'description' => 'Romantic stories'],
            ['name' => 'Animation', 'description' => 'Animated films'],
            ['name' => 'Documentary', 'description' => 'Educational documentaries'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                array_merge($category, ['slug' => str()->slug($category['name'])])
            );
        }
    }
}
