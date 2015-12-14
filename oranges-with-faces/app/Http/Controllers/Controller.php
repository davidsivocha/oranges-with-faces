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
            $orderData = new OrderValueObject($request->all());
            $validateShipping = Validator::make($orderData->getAllData(), $orderData->getValidationRules(), $orderData->getValidationMessages());

            if($validateShipping->fails()){
                throw new Exception($validateShipping->errors());
            }

            Stripe::setApiKey(env('STRIPE_SECRET'));
            $charge = StripeCharge::create([
                "amount"               => Order::COST,
                "currency"             => Order::CURRENCY,
                "source"               => $orderData->getToken(),
                "description"          => "An orange with a face",
                "receipt_email"        => $orderData->getEmail(),
                "statement_descriptor" => "OrangesFaces"
            ]);

            $orderData = $orderData->getEloquentData();
            $orderData['charge_id']   = $charge->id;
            $orderData['total_cost']  = $charge->amount;
            $orderData['currency']    = $charge->currency;
            $order = Order::create($orderData);

            Mail::send('emails.customer.order', ['order' => $order], function ($m) use ($order) {
                $m->to($order->customer_email, $order->customer_name)->subject('Thanks for Buying an Orange with a Face!');
            });

            Mail::send('emails.admin.neworder', ['order' => $order], function ($m) use ($order) {
                $m->to(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))->subject('New Order!');
            });

            return response()->json(['success' => true]);
        } catch(CardError $sce) {
            return response()->json(['error' => $sce->getMessage()], 401);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function postContact(Request $request)
    {
        $contact = $request->all();

        Mail::send('emails.admin.contact', ['contact' => $contact], function ($m) use ($contact) {
            $m->to(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))->subject('Contact Message');
        });

        return response()->json(['success' => true]);
    }

    public function markDispatched($id)
    {
        $order = Order::findOrFail($id);
        if($order->status == 'dispatched') {
            return 'Order already marked as Dispatched';
        } else if ($order->status == 'canceled') {
            return "Order was marked as canceled and can't be dispatched";
        }

        $order->status = 'dispatched';
        $order->save();

        Mail::send('emails.customer.dispatched', ['order' => $order], function ($m) use ($order) {
            $m->to($order->customer_email, $order->customer_name)->subject('Your orange has been Dispatched');
        });

        return 'Marked as Dispatched';
    }
}
