<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandFollower extends Model
{
    protected $table = 'brandfollower';
    protected $guarded = ['id'];
    public const CREATED_AT = 'followed_at';
    public const UPDATED_AT = null;
}
