<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'description', 'price', 'image', 'category_id', 'popularity'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    
        use SoftDeletes;
    
        protected $dates = ['deleted_at'];
    }

