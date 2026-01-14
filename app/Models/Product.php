<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'stock',
        'image',
    ];

    protected $casts = [
        'image' => 'array',
    ];

    public function orderdetails()
    {
        return $this->hasMany(Orderdetail::class);
    }
}
