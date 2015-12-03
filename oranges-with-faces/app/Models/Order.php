<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const TABLE = 'orders';
    const CURRENCY = 'gbp';
    const COST = 1000;

    const STATUS_CREATED = 'created';
    const STATUS_SENT = 'sent';

    protected $table = self::TABLE;

    public $timestamps = true;

    public $fillable = [
        'customer_email',
        'customer_name',
        'shipping_name',
        'shipping_address_1',
        'shipping_address_2',
        'shipping_city',
        'shipping_county',
        'shipping_post_code',
        'status',
        'charge_id',
        'customer_id',
        'total_cost',
        'currency'
    ];
}
