<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PortioningCategory;

class PortioningOrderDetail extends Model
{
    use HasFactory;
    protected $primaryKey = 'order_detail_id';
    protected $fillable = [
        'order_head_id',
        'portioning_category_id',
        'scheduled_day',
        'letter',
        'component_details',
        'label',
        'allergen',
        'weight',
        'packaging',
        'quantity',
        'film_size',
        '95_percent',
        'status',
        'created_at',
        'updated_at',
    ];

    public function category(){
        return $this->belongsTo(PortioningCategory::class, 'portioning_category_id', 'category_id');
    }
}
