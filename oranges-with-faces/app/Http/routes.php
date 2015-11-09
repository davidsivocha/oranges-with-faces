<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->welcome();
});

$app->post('/charge', function () use($app) {
    $stripeKey = env('STRIPE_KEY');
    $stripeSecret = env('STRIPE_SECRET');

    \Stripe\Stripe::setApiKey($stripeSecret);

    $token = $_POST['stripeToken'];

    $validateShipping = Validator::make(data, rules, messages, customAttributes);
    if($validator->fails()){
        throw new Exception();
    }

    $charge = \Stripe\Charge::create(array(
        "amount" => 1000,
        "currency" => "gbp",
        "source" => $token,
        "description" => "Example charge"
    ));

    Order::create([
        'charge_id'          => $test,
        'customer_id'        => $test,
        'billing_name'       => $test,
        'billing_address_1'  => $test,
        'billing_city'       => $test,
        'billing_state'      => $test,
        'billing_country'    => $test,
        'billing_zip'        => $test,
        'shipping_name'      => $test,
        'shipping_address_1' => $test,
        'shipping_city'      => $test,
        'shipping_state'     => $test,
        'shipping_country'   => $test,
        'shipping_zip'       => $test,
        'total_cost'         => $test
    ]);
    return $app->welcome();
});
