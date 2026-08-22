<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{
    protected $table = 'CartDetail';

    protected $primaryKey = 'CartDetailID';

    public $timestamps = false;

    protected $fillable = [
        'CartID',
        'ServiceID',
        'Quantity',
    ];

    public function cart()
    {
        return $this->belongsTo(
            Cart::class,
            'CartID',
            'CartID'
        );
    }

    public function service()
    {
        return $this->belongsTo(
            Service::class,
            'ServiceID',
            'ServiceID'
        );
    }
}