<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const CURRENCY = 'gbp';
    const COST = 1000;

    const STATUS_CREATED = 'created';
    const STATUS_SENT = 'sent';

    public $timestamps = true;

    public $fillable = [
        'charge_id',
        'customer_id',
        'customer_email',
        'shipping_name',
        'shipping_address_1',
        'shipping_address_2',
        'shipping_city',
        'shipping_county',
        'shipping_post_code',
        'total_cost',
        'currency',
        'status'
    ];
}
