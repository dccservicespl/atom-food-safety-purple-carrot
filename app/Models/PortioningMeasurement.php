<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortioningMeasurement extends Model
{
    use HasFactory;
    protected $fillable = [
        'measure_id',
        'item_id',
        'measure_date',
        'measure_time',
        'measure_by',
        'equipment',
        'table',
        'pre_op_complete',
        'people_qty',
        'scale',
        'lot_number',
        'temperature',
        'allergen',
        'allergen_result',
        'pack_size',
        'kit_letter',
        'qty_produces_final',
        'fs_initial',
        'attachment',
        'description',
        'reviewed_by',
    ];

    protected $casts = [
        'measure_date' => 'date',
        'measure_time' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo(PurpleCarrotItemMst::class, 'item_id');
    }

    public function measuredBy()
    {
        return $this->belongsTo(User::class, 'measure_by');
    }

    public function measureHead()
    {
        return $this->belongsTo(PortioningMeasureHead::class, 'measure_id');
    }

    public function samples()
    {
        return $this->hasMany(PortioningMeasurementSample::class, 'measure_id');
    }

    public function reviewedByUser()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function item_details()
    {
        return $this->belongsTo(PortioningOrderDetail::class, 'item_id');
    }

    public function orderDetail()
    {
        return $this->belongsTo(PortioningOrderDetail::class, 'item_id', 'order_detail_id');
    }
}
