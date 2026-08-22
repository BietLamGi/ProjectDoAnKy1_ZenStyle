<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'Service';

    protected $primaryKey = 'ServiceID';

    public $timestamps = false;    
     protected $fillable = [
        'ServiceType',
        'Category',
        'ServiceName',
        'Description',
        'DurationMinutes',
        'Price',
        'IsActive',
        'Image',
    ];

    public function activePromotion()
{
    return $this->hasOne(
        Promotion::class,
        'ServiceID',
        'ServiceID'
    )
    ->where('IsActive', 1)
    ->whereDate('StartDate', '<=', today())
    ->whereDate('EndDate', '>=', today())
    ->orderByDesc('PromotionID');
}

public function getDiscountedPriceAttribute()
{
    $promotion = $this->activePromotion;

    if (!$promotion) {
        return $this->Price;
    }

    if ($promotion->DiscountType === 'Percent') {
        return max(
            0,
            $this->Price - ($this->Price * $promotion->DiscountValue / 100)
        );
    }

    if ($promotion->DiscountType === 'Fixed') {
        return max(
            0,
            $this->Price - $promotion->DiscountValue
        );
    }

    return $this->Price;
}
}