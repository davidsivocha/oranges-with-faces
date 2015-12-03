<?php

namespace App\ValueObjects;

use App\Models\Order as OrderModel;

class Order
{
    protected $token;
    protected $customer_email;
    protected $customer_name;
    protected $shipping_name;
    protected $shipping_address_1;
    protected $shipping_address_2;
    protected $shipping_city;
    protected $shipping_county;
    protected $shipping_post_code;
    protected $status;

    public function __construct($data)
    {
        $this->token              = array_key_exists('token', $data) ? $data['token'] : null;
        $this->customer_email     = array_key_exists('emailAddress', $data) ? $data['emailAddress'] : null;
        $this->customer_name      = array_key_exists('buyerName', $data) ? $data['buyerName'] : null;
        $this->shipping_name      = array_key_exists('victimName', $data) ? $data['victimName'] : null;
        $this->shipping_address_1 = array_key_exists('address1', $data) ? $data['address1'] : null;
        $this->shipping_address_2 = array_key_exists('address2', $data) ? $data['address2'] : null;
        $this->shipping_city      = array_key_exists('city', $data) ? $data['city'] : null;
        $this->shipping_county    = array_key_exists('county', $data) ? $data['county'] : null;
        $this->shipping_post_code = array_key_exists('postCode', $data) ? $data['postCode'] : null;
        $this->status             = OrderModel::STATUS_CREATED;
    }

    public function getEloquentData()
    {
        return [
            'customer_email'        => $this->customer_email,
            'customer_name'         => $this->customer_name,
            'shipping_name'         => $this->shipping_name,
            'shipping_address_1'    => $this->shipping_address_1,
            'shipping_address_2'    => $this->shipping_address_2,
            'shipping_city'         => $this->shipping_city,
            'shipping_county'       => $this->shipping_county,
            'shipping_post_code'    => $this->shipping_post_code,
            'status'                => $this->status
        ];
    }

    public function getAllData()
    {
        return [
            'token'                 => $this->token,
            'customer_email'        => $this->customer_email,
            'customer_name'         => $this->customer_name,
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

    public function getEmail()
    {
        return $this->customer_email;
    }

    public function getValidationRules()
    {
        return [
            'token'                 => [
                'required',
                'string',
                'max:255'
            ],
            'customer_email'        => [
                'required',
                'string',
                'email',
                'max:255'
            ],
            'customer_name'         => [
                'required',
                'string',
                'max:255'
            ],
            'shipping_name'         => [
                'required',
                'string',
                'max:255'
            ],
            'shipping_address_1'    => [
                'required',
                'string',
                'max:255'
            ],
            'shipping_address_2'    => [
                'string',
                'max:255'
            ],
            'shipping_city'         => [
                'required',
                'string',
                'max:255'
            ],
            'shipping_county'       => [
                'required',
                'string',
                'max:255'
            ],
            'shipping_post_code'    => [
                'required',
                'string',
                'regex:/^(([gG][iI][rR] {0,}0[aA]{2})|((([A-PR-UWYZ][A-HK-Y]?[0-9][0-9]?)|(([A-PR-UWYZ][0-9][A-HJKSTUW])|([A-PR-UWYZ][a-hk-yA-HK-Y][0-9][ABEHMNPRV-Y]))) {0,}[0-9][ABD-HJLNP-UW-Z]{2}))$/i'
            ]
        ];
    }

    public function getValidationMessages()
    {
        return [
            'token.required'                => "It looks like there was a problem with the token",
            'token.string'                  => "The token should be a string",
            'token.max'                     => "The token shouldn't be more than 255 characters long",
            'customer_email.required'       => "You need to enter an email address",
            'customer_email.string'         => "The email address should be a string",
            'customer_email.email'          => "It looks like you've given us an invalid email",
            'customer_email.max'            => "The email should be less than 255 characters long",
            'customer_name.required'        => "You need to enter your name",
            'customer_name.string'          => "Your name should be a string",
            'customer_name.max'             => "Your name should be less than 255 characters long",
            'shipping_name.required'        => "You need to enter the name of the person who you're sending the orange to",
            'shipping_name.string'          => "The persons name should be a string",
            'shipping_name.max'             => "The persons name shouldn't be longer than 255 characters",
            'shipping_address_1.required'   => "Address Line 1 is required",
            'shipping_address_1.string'     => "Address Line 1 should be a string",
            'shipping_address_1.max'        => "Address Line 1 shouldn't be more than 255 characters",
            'shipping_address_2.string'     => "Address Line 2 should be a string",
            'shipping_address_2.max'        => "Address Line 2 shouldn't be more than 255 characters",
            'shipping_city.required'        => "You need to enter a City",
            'shipping_city.string'          => "The city should be a string",
            'shipping_city.max'             => "The city shouldn't be more than 255 characters",
            'shipping_county.required'      => "You need to enter a county",
            'shipping_county.string'        => "The county should be a string",
            'shipping_county.max'           => "The county shouldn't be more than 255 characters long",
            'shipping_post_code.required'   => "You need to give us the post code!",
            'shipping_post_code.string'     => "The post code should be a string",
            'shipping_post_code.regex'      => "It looks like that isn't a valid post code, can you check it and try again"
        ];
    }
}
