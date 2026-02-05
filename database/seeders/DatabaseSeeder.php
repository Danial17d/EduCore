<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
        ]);

        $categories = Category::factory()
            ->count(4)
            ->create(['type' => 'course']);

        Course::factory()
            ->count(12)
            ->state(fn () => ['category_id' => $categories->random()->id])
            ->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $user = User::where(['name' => 'Test User'])->first();
        $user->assignRole('admin');
    }
}
