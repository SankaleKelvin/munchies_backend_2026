<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        "restaurant_id",
        "category_id",
        "name"
    ];
}
