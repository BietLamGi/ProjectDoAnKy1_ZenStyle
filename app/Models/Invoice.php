<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'Invoice';

    protected $primaryKey = 'InvoiceID';

    public $timestamps = false;

    protected $fillable = [
        'AppointmentID',
        'CustomerID',
        'InvoiceDate',
        'TotalAmount',
        'DiscountAmount',
        'FinalAmount',
        'PaymentMethod',
        'ShippingName',
        'ShippingPhone',
        'ShippingAddress',
    ];

    protected $casts = [
        'InvoiceDate' => 'datetime',
        'TotalAmount' => 'decimal:2',
        'DiscountAmount' => 'decimal:2',
        'FinalAmount' => 'decimal:2',
    ];

    public function appointment()
    {
        return $this->belongsTo(
            Appointment::class,
            'AppointmentID',
            'AppointmentID'
        );
    }

    public function details()
    {
        return $this->hasMany(
            InvoiceDetail::class,
            'InvoiceID',
            'InvoiceID'
        );
    }
    
    public function customer()
    {
        return $this->belongsTo(
            Customer::class,
            'CustomerID',
            'CustomerID'
        );
    }
    
}