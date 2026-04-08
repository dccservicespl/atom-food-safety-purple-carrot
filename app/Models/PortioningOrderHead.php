<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortioningOrderHead extends Model
{
    use HasFactory;
    protected $primaryKey = 'order_head_id';
    protected $fillable = [
        'week',
        'from_date',
        'to_date',
        'updated_by',
    ];
}
