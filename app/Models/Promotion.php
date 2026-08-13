<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $table = 'Promotion';
    protected $primaryKey = 'PromotionID';

    public $timestamps = false;

    protected $fillable = [
        'Title',
        'ServiceID',
        'Description',
        'DiscountType',
        'DiscountValue',
        'StartDate',
        'EndDate',
        'IsActive',
    ];

    protected $casts = [
        'DiscountValue' => 'decimal:2',
        'StartDate' => 'date',
        'EndDate' => 'date',
        'IsActive' => 'boolean',
    ];

    public function service()
    {
        return $this->belongsTo(
            Service::class,
            'ServiceID',
            'ServiceID'
        );
    }
}