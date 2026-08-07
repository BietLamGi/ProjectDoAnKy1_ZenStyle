<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'Customer';

protected $primaryKey = 'CustomerID';

public $timestamps = false;

protected $fillable = [
    'FullName',
    'Phone',
    'Email',
    'DOB',
    'Allergies',
    'Notes',
    'LoyaltyPoints',
    'MembershipTier',
];
}
