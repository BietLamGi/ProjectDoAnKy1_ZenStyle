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
        'InvoiceDate',
        'TotalAmount',
        'DiscountAmount',
        'FinalAmount',
        'PaymentMethod',
        'CustomerID',
    ];

    protected $casts = [
        'InvoiceDate' => 'datetime',
        'TotalAmount' => 'decimal:2',
        'DiscountAmount' => 'decimal:2',
        'FinalAmount' => 'decimal:2',
    ];
}