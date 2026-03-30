<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'wishlist';
    protected $guarded = ['id'];
    public const CREATED_AT = 'added_at';
    public const UPDATED_AT = null;
}
