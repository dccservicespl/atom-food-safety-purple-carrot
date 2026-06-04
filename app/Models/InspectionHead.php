<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionHead extends Model
{
    use HasFactory;
    protected $fillable = ['daily_measure_id'];
}
