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
        'portioning_order_head_id',
        'portioning_category_id',
        'scheduled_day',
        'order_details_id',
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
    public function measure_by()
    {
        return $this->belongsTo(User::class, 'measure_by');
    }

    public function portioning_category()
    {
        return $this->belongsTo(PortioningCategory::class, 'portioning_category_id');
    }
}
