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

$app->post('/order', function (Request $request) use($app) {
    $stripeKey = env('STRIPE_KEY');
    $stripeSecret = env('STRIPE_SECRET');

    \Stripe\Stripe::setApiKey($stripeSecret);

    $token = $request->input('stripeToken');

    $validateShipping = Validator::make(data, rules, messages, customAttributes);
    if($validator->fails()){
        throw new Exception();
    }

    $charge = \Stripe\Charge::create(array(
        "amount" => Order::COST,
        "currency" => Order::CURRENCY,
        "source" => $token,
        "description" => "An orange with a face"
    ));

    Order::create([
        'charge_id'          => $charge->id,
        'customer_id'        => $charge->customer,
        'customer_email'     => $request->input('stripeEmail'),
        'billing_name'       => $request->input('stripeBillingName'),
        'billing_address_1'  => $request->input('stripeBillingAddressLine1'),
        'billing_city'       => $request->input('stripeBillingAddressCity'),
        'billing_state'      => $request->input('stripeBillingAddressState'),
        'billing_country'    => $request->input('stripeBillingAddressCountry'),
        'billing_zip'        => $request->input('stripeBillingAddressZip'),
        'shipping_name'      => $request->input('stripeShippingName'),
        'shipping_address_1' => $request->input('stripeShippingAddressLine1'),
        'shipping_city'      => $request->input('stripeShippingAddressCity'),
        'shipping_state'     => $request->input('stripeShippingAddressState'),
        'shipping_country'   => $request->input('stripeShippingAddressCountry'),
        'shipping_zip'       => $request->input('stripeShippingAddressZip'),
        'total_cost'         => $charge->amount,
        'currency'           => $charge->currency,
        'status'             => Order::STATUS_CREATED
    ]);
    return $app->welcome();
});

$app->get('/admin', function () use ($app) {

});
