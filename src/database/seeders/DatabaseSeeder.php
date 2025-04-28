<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Comment;
use App\Models\Order;
use App\Models\OrderItem;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Создание пользователей
        User::factory()->count(10)->create();

        // Создание категорий
        Category::factory()->count(5)->create();

        // Создание товаров
        Product::factory()->count(50)->create();

        // Создание комментариев
        Comment::factory()->count(100)->create();

        // Создание заказов с элементами
        Order::factory()->count(20)->create()->each(function ($order) {
            OrderItem::factory()->count(rand(1, 5))->create([
                'order_id' => $order->id,
            ]);
        });
    }
}
