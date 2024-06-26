<?php

namespace App\Http\Controllers;

use App\Mail\ConfigTest;
use App\Mail\OrderConfirmation;
use App\Models\Hotel;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use \Stripe\Stripe;

use App\Helpers\Money;

class CheckoutController extends Controller
{
    public function initiateCheckout($hotel_id)
    {

        $cart = session()->get('cart');


        $name = session()->get('name');
        $arrival_date = session()->get('arrival_date');
        $departure_date = session()->get('departure_date');
        $email_address = session()->get('email_address');

        $order = new Order();
        if (is_numeric($hotel_id)) {
            $order->hotel_id = $hotel_id;
        } else {
            $hotel = Hotel::where('slug', $hotel_id)->first();
            $order->hotel_id = $hotel->id;
        }
        $order->name = $name;
        $order->email = $email_address;
        $order->arrival_date = $arrival_date;
        $order->departure_date = $departure_date;
        $order->payment_status = 'pending';
        $order->subtotal = $cart['total'];
        $order->total_tax = $cart['tax'];
        $order->total = $cart['total_with_tax'];

        $order->save();

        $items = [];
//        dd($cart);
        foreach ($cart as $item) {
            if (is_array($item)) {

                //This is the OrderItem model which we store in the database
                $OrderItem = new OrderItem();

                $OrderItem->order_id = $order->id;
                $OrderItem->product_id = $item['product_id'];
                $OrderItem->variation_id = $item['variation_id'];
                $OrderItem->product_name = $item['product_name'];
                $OrderItem->variation_name = $item['variation_name'];
                $OrderItem->quantity = $item['quantity'];
                $OrderItem->price = $item['price'];
                $OrderItem->image = $item['image'];
                $OrderItem->date = $item['date'];
                $OrderItem->product_type = $item['product_type'];

                $OrderItem->save();


                //This $items array is the one we send to Stripe
                $items[] = [
                    'price_data' => [
                        'currency' => 'gbp',
                        'product_data' => [
                            'name' => $item['product_name'],
                            'description' => $item['variation_name'],
                            'images' => [$item['image']],

                        ],
                        'unit_amount' => $item['price'] * 100,
                    ],
                    'quantity' => $item['quantity'],
//                    'tax_rates' => ['txr_1P3JhAJQ5u1m2fEs9mBMawIb']
                ];
            }
        }

        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET_KEY')
        );

        $checkout_session = $stripe->checkout->sessions->create([
            'success_url' => env('APP_URL') . '/checkout/complete',
            'cancel_url' => env('APP_URL') . '/checkout/cancelled',
            'payment_method_types' => ['card'],
            'line_items' => [
                $items
            ],
            'customer_email' => $email_address,
            'mode' => 'payment',
            'metadata' => [
                'payment_type' => 'hotel_item_order',
                'order_id' => $order->id, // This is the order ID from your system
                'name' => $name,
                'arrival_date' => $arrival_date,
                'hotel_id' => $hotel_id
            ]
        ]);

//        header("HTTP/1.1 303 See Other");
//        header("Location: " . $checkout_session->url);

        if (!$cart) {
            return redirect()->back()->with('error', 'Cart is empty!');
        }

        return Redirect::to($checkout_session->url);
    }

    public function checkoutSessionWebhook(Request $request)
    {

        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            Mail::to('alex@gluestudio.co.uk', 'Alex')->send(new ConfigTest(json_encode($e)));
            http_response_code(400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            Mail::to('alex@gluestudio.co.uk', 'Alex')->send(new ConfigTest(json_encode($e)));
            http_response_code(400);
            exit();
        }

        error_log("Passed signature verification!");
        http_response_code(200);

        if ($event->type == 'checkout.session.completed') {
            if ($event->data->object->metadata->payment_type === 'hotel_item_order') {
                $session = \Stripe\Checkout\Session::retrieve([
                    'id' => $event->data->object->id,
                    'expand' => ['line_items'],
                ]);

//                Mail::to('alex@gluestudio.co.uk', 'Alex')->send(new ConfigTest(json_encode($event)));
                $line_items = $session->line_items;


                $order = Order::find($session->metadata->order_id);

                $order->stripe_id = $session->id;
                $order->payment_status = $session->payment_status;

                $order->save();

                Mail::to($session->customer_details->email, $session->metadata->name)->send(new OrderConfirmation($order));
//                Mail::to('alex@gluestudio.co.uk', 'Alex')->send(new ConfigTest(json_encode($payload)));

                session()->forget('cart');

                return response()->json(['success' => 'Order created successfully']);
            } else {
//                Mail::to('alex@gluestudio.co.uk', 'Alex')->send(new ConfigTest(json_encode($event)));

                $client_reference_id = $event->data->object->client_reference_id;
                //Remove the string 'USER_' from the client_reference_id
                $client_reference_id = substr($client_reference_id, 5);
                $user = User::find($client_reference_id);
                $user->account_status = 'active';
                $user->save();
                return response()->json(['success' => 'User account activated successfully']);

            }


        }


    }

    public function checkoutComplete()
    {

        $hotel = Hotel::find(session()->get('hotel_id'));
        $cartItems = session()->get('cart');
        session()->forget('cart');
//dd($cartItems);
//        Mail::to('alex@gluestudio.co.uk', 'Alex')->send(new ConfigTest(json_encode($payload)));
        return view('checkout.complete', ['cartItems' => $cartItems, 'hotel' => $hotel]);

    }

    public function checkoutCancelled()
    {
        return view('checkout.cancelled');
    }
}
