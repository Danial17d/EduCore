<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->words(3, true);

        return [
            'category_id' => Category::factory()->state(['type' => 'course']),
            'name' => Str::title($title),
            'slug' => Str::slug($title),
            'credit' => $this->faker->numberBetween(1, 6),
            'code' => strtoupper($this->faker->unique()->bothify('CRS-###')),
            'description' => $this->faker->sentence(16),
            'status' => 'active',
        ];
    }
}
