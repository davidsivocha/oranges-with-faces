<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $timestamps = true;

    public $fillable = [
        'charge_id',
        'customer_id',
        'billing_name',
        'billing_address_1',
        'billing_city',
        'billing_state',
        'billing_country',
        'billing_zip',
        'shipping_name',
        'shipping_address_1',
        'shipping_city',
        'shipping_state',
        'shipping_country',
        'shipping_zip',
        'total_cost'
    ];
}
