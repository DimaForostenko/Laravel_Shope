<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition()
    {
        $name = $this->faker->words(2, true);
        return [
            'name' => $name,
            'slug' => Str::slug($name). '-' . uniqid(),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'image' => $this->faker->imageUrl(),
            'category_id' => Category::factory(),
            'popularity' => $this->faker->numberBetween(0, 100),
        ];
    }
}
