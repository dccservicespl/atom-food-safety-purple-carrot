<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortioningMeasurementSample extends Model
{
    use HasFactory;

    protected $fillable = [
        'measure_id',
        'item_id',
        'sample_number',
        'sample_value',
    ];

    protected $casts = [
        'sample_value' => 'decimal:2',
    ];

    public function measurement()
    {
        return $this->belongsTo(PortioningMeasurement::class, 'measure_id');
    }
}
