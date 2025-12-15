<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'customerNumber';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'customerNumber',
        'customerName',
        'contactLastName',
        'contactFirstName',
        'phone',
        'addressLine1',
        'addressLine2',
        'city',
        'state',
        'postalCode',
        'country',
        'salesRepEmployeeNumber',
        'creditLimit'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'customerNumber', 'customerNumber');
    }
}
