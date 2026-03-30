<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'orderitem';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
