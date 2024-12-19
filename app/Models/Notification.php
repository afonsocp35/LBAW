<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Notification extends Model
{

    protected $table = 'notifications';


    public function review()
    {
        return $this->belongsTo(Review::class, 'review_id');
    }
}
