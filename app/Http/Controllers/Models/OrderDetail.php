<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    //
    protected $table = 'order_details';

    protected $fillable = [
        'order_id',
        'service_id',
        'service_name',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'ServiceID');
    }
}
