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
    $charge = \Stripe\Charge::create(array(
        "amount" => 1000,
        "currency" => "gbp",
        "source" => $token,
        "description" => "Example charge"
    ));
    return $app->welcome();
});
