<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAccess extends Model
{
    protected $table = 't1000_sso_user_access_app';

    protected $fillable = [
        'id_user',
        'app_drawing',
        'app_inventory',
        'app_npc',
        'app_dashboard',
    ];

    public $timestamps = false; // Assuming no timestamps in that table based on the screenshot

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
