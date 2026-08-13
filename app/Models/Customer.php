<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'Customer';

    protected $primaryKey = 'CustomerID';

    public $timestamps = false;

    protected $fillable = [
        'UserID',
        'FullName',
        'Phone',
        'Email',
        'DOB',
        'Allergies',
        'Notes',
        'LoyaltyPoints',
        'MembershipTier',
    ];
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'CustomerID', 'CustomerID');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'CustomerID', 'CustomerID');
    }

}