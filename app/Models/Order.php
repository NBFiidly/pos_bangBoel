<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function customer ()
    {
        return $this->belongsTo(Customer::class);
    }
    protected $fillable = [
        'customer_id',
        'total_price',
        'date',
    ];
    public function orderdetails()
    {
        return $this->hasMany(Orderdetail::class);
    }
}
