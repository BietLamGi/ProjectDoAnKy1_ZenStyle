<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'Cart';

    protected $primaryKey = 'CartID';

    public $timestamps = false;

    protected $fillable = [
        'UserID',
        'CreatedAt',
        'UpdatedAt',
    ];

    public function details()
    {
        return $this->hasMany(
            CartDetail::class,
            'CartID',
            'CartID'
        );
    }
}