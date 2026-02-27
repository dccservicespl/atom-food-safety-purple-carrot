<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortioningMeasureHead extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_time',
        'end_time',
        'measure_by',
        'equipment',
        'table_name',
        'people_qty',
        'scale',
        'pre_op_complete',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'pre_op_complete' => 'boolean',
    ];

    public function operator()
    {
        return $this->belongsTo(User::class, 'measure_by');
    }
}
