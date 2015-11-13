<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge as StripeCharge;
use Validator;
use Mail;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Order;
use App\ValueObjects\Order as OrderValueObject;
use Stripe\Error\Card as CardError;

class Controller extends BaseController
{
    public function getIndex()
    {
        return view('home');
    }

    public function postOrder(Request $request)
    {
        try {
            $stripeSecret = env('STRIPE_SECRET');
            Stripe::setApiKey($stripeSecret);
            $orderData = new OrderValueObject($request->all());

            $validateShipping = Validator::make($orderData->getEloquentData(), $orderData->getValidationRules(), $orderData->getValidationMessages());

            if($validateShipping->fails()){
                throw new Exception($validateShipping->errors());
            }

            $charge = StripeCharge::create([
                "amount"        => Order::COST,
                "currency"      => Order::CURRENCY,
                "source"        => $orderData->getToken(),
                "description"   => "An orange with a face"
            ]);

            $orderData = $orderData->getEloquentData();
            $orderData['charge_id'] = $charge->id;
            $orderData['customer_id'] = $charge->customer;
            $orderData['total_cost'] = $charge->amount;
            $orderData['currency'] = $charge->currency;
            $order = Order::create($orderData);

            Mail::send('emails.customer.order', ['order' => $order], function ($m) use ($order) {
                $m->to($order->customer_email)->subject('Thanks for Buying an Orange with a Face!');
            });

            Mail::send('emails.admin.neworder', ['order' => $order], function ($m) use ($order) {
                $m->to(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))->subject('New Order!');
            });

            return response()->json(['success' => true]);
        } catch(CardError $sce) {
            return response()->json(['cardError' => $sce->getMessage()], 401);
        } catch (Exception $e) {
            return response()->json(['general' => [$e->getMessage()]], 500);
        }
    }

    public function postOrderStatus(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);
            $newStatus = $request->input('status');
            if($newStatus == Order::STATUS_SENT) {
                $order->status = Order::STATUS_SENT;
                $order->save();
                Mail::send('emails.customer.dispatched', ['order' => $order], function ($m) use ($order) {
                    $m->to($order->customer_email)->subject('Your Orange has been sent!');
                });
            } else {
                throw new Exception("Unknown Order Status");
            }
        } catch (Exception $e) {
            return response()->json(['general' => [$e->getMessage()]], 500);
        }
    }
}
