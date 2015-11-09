<?php

namespace App\ValueObjects;

use App\Models\Order as OrderModel;

class Order
{
    protected $token;
    protected $customer_email;
    protected $shipping_name;
    protected $shipping_address_1;
    protected $shipping_address_2;
    protected $shipping_city;
    protected $shipping_county;
    protected $shipping_post_code;
    protected $status;

    public function __construct($data)
    {
        $this->token = array_key_exists('stripeToken', $data) ? $data['stripeToken'] : null;
        $this->customer_email = array_key_exists('customerEmail', $data) ? $data['customerEmail'] : null;
        $this->shipping_name = array_key_exists('shippingName', $data) ? $data['shippingName'] : null;
        $this->shipping_address_1 = array_key_exists('shippingAddress1', $data) ? $data['shippingAddress1'] : null;
        $this->shipping_address_2 = array_key_exists('shippingAddress2', $data) ? $data['shippingAddress2'] : null;
        $this->shipping_city = array_key_exists('shippingCity', $data) ? $data['shippingCity'] : null;
        $this->shipping_county = array_key_exists('shippingCounty', $data) ? $data['shippingCounty'] : null;
        $this->shipping_post_code = array_key_exists('shippingPostCode', $data) ? $data['shippingPostCode'] : null;
        $this->status = OrderModel::STATUS_CREATED;
    }

    public function getEloquentData()
    {
        return [
            'token'                 => $this->token,
            'customer_email'        => $this->customer_email,
            'shipping_name'         => $this->shipping_name,
            'shipping_address_1'    => $this->shipping_address_1,
            'shipping_address_2'    => $this->shipping_address_2,
            'shipping_city'         => $this->shipping_city,
            'shipping_county'       => $this->shipping_county,
            'shipping_post_code'    => $this->shipping_post_code,
            'status'                => $this->status
        ];
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getValidationRules()
    {
        return [
            'token'                 => [],
            'customer_email'        => [],
            'shipping_name'         => [],
            'shipping_address_1'    => [],
            'shipping_address_2'    => [],
            'shipping_city'         => [],
            'shipping_county'       => [],
            'shipping_post_code'    => [],
            'status'                => []
        ];
    }
}
