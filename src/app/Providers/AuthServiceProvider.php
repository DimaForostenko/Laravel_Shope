<?php

namespace App\Providers;
use App\Models\Comment;
use App\Models\Order;
use App\Policies\CommentPolicy;
use App\Policies\OrderPolicy;
// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Comment::class => CommentPolicy::class,
        Order::class => OrderPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
