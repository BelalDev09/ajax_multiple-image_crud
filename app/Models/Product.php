<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'description',
        'price',
        'status',
        'medias'

    ];
    protected $casts = [
        'medias' => 'array',
    ];
    public function category()
    {
        $this->belongsTo(Category::class);
    }
}
