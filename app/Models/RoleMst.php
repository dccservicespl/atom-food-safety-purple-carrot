<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleMst extends Model
{
    use HasFactory;
    protected $table = 'role_msts';

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
