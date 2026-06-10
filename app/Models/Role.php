<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['role_name', 'scope_id', 'description'];

    public function scope()
    {
        return $this->belongsTo(Scope::class, 'scope_id');
    }
}
