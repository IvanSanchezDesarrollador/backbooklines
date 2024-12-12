<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'discountPercentage',
        'rating',
        'stock',
        'author',
        'category',
    ];

    public function imagenes(): MorphMany
    {
        return $this->morphMany(Images::class, 'imageable')->where('type' , 'image');
    }

    public function thumbnail(): MorphMany
    {
        return $this->morphMany(Images::class, 'imageable')->where('type' , 'thumbnail');
    }
}
