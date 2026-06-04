<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scope extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'scope_name', 'is_active'];
}
