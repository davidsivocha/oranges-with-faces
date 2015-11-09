<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const CURRENCY = 'gbp';
    const COST = 1000;

    const STATUS_CREATED = 'created';
    const STATUS_SENT = 'sent';
    const STATUS_RECEIVED = 'received';

    public $timestamps = true;

    public $fillable = [
        'charge_id',
        'customer_id',
        'customer_email',
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
        'total_cost',
        'currency',
        'status'
    ];
}
