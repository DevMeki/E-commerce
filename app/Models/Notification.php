<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notification';
    protected $guarded = ['id'];
    public const UPDATED_AT = null;
}
