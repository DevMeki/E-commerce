<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';
    protected $guarded = ['id'];
    public const CREATED_AT = 'added_at';
    public const UPDATED_AT = null;
}
