<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\PurpleCarrotCategory;

class PurpleCarrotItemMst extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'component_details',
        'label',
        'unite',
        'status',
    ];

    /**
     * Get the category for this item
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(PurpleCarrotCategory::class, 'category_id');
    }
}
