<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTemplate extends Model
{
    use HasFactory;
    
    protected $table = 'ProductTemplate';
    public $timestamps  = false;
    protected $fillable = [
        'name',
        'creator',
        'description'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'template');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'template');
    }

    public function getName(): string
    {
        return $this->name;
    }
}
