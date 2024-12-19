<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;
    protected $table = 'ProductImage';
    public $timestamps  = false;

    // Set the primary key as non-incrementing and composite
    protected $primaryKey = ['template'];
    public $incrementing = false;

    protected $fillable = [
        'template', // Reference to the ProductTemplate ID
        'index',    // Index of the image
        'path',     // Path of the uploaded image
    ];
}
