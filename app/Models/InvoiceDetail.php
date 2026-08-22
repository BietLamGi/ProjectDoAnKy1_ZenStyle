<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    protected $table = 'InvoiceDetail';

    protected $primaryKey = 'InvoiceDetailID';

    public $timestamps = false;

    protected $fillable = [
        'InvoiceID',
        'ServiceID',
        'Quantity',
        'UnitPrice',
    ];

    public function invoice()
    {
        return $this->belongsTo(
            Invoice::class,
            'InvoiceID',
            'InvoiceID'
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