<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $table = 'orders';

    protected $fillable = [
        'customer_id',
        'appointment_id',
        'receptionist_id',
        'total_amount',
        'payment_method',
        'payment_status',
        'note',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'CustomerID');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'AppointmentID');
    }

    public function receptionist()
    {
        return $this->belongsTo(User::class, 'receptionist_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }
}
