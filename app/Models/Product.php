<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'Product';

    protected $fillable = [
        'price',
        'stock',
    ];

    // todo: fix naming
    public function _seller(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'seller');
    }

    public function _template(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductTemplate::class, 'template');
    }

    public function platforms(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProductPlatform::class, 'product');
    }
}
