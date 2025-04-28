<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition()
    {
        return [
            'content' => $this->faker->paragraph(),
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
        ];
    }
}
