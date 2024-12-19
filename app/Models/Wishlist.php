<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Wishlist extends Model
{

    protected $table = 'Wishlist';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
