<?php

namespace App\Http\Controllers;

use App\Models\Variation;
use Illuminate\Http\Request;

class CartController extends Controller
{
    function show($hotel_id)
    {
        $data['cart'] = session()->get('cart');
        return view('cart.show', ['data' => $data])->with('hotel_id', $hotel_id);
    }

    function addToCart(Request $request)
    {
        $items = $request->all()['formObj'];

        $id = $items['variation_id'];
        $quantity = $items['quantity'];
        $product_id = $items['product_id'];
        $hotel_id = $items['hotel_id'];
        $product_name = $items['product_name'];

        $product = Variation::find($id);

        if (!$product) {

            abort(404);
        }

        $cart = session()->get('cart');
        if (!$cart) {

            $cart = [
                $id => [
                    'product_id' => $product_id,
                    'hotel_id' => $hotel_id,
                    'product_name' => $product_name,
                    "name" => $product->name,
                    "quantity" => $quantity,
                    "price" => $product->price,
                    "image" => $product->image
                ]
            ];
            $cart = $this->calculateCartTotals($cart);
            session()->put('cart', $cart);

            return json_encode(['message' => 'Product added to cart successfully!', 'cart' => $cart]);
        }

//        dd($cart);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
            $cart = $this->calculateCartTotals($cart);
            session()->put('cart', $cart);
            return json_encode(['message' => 'Product added to cart successfully!', 'cart' => $cart]);
        }


        $cart[$id] = [
            'product_id' => $product_id,
            'hotel_id' => $hotel_id,
            'product_name' => $product_name,
            "name" => $product->name,
            "quantity" => $quantity,
            "price" => $product->price,
            "image" => $product->image
        ];
        $cart = $this->calculateCartTotals($cart);

        $cartCount = 0;
        foreach ($cart as $item) {
            if (is_array($item)) {
                $cartCount++;
            }
        }
        session()->put('cart', $cart);

        return json_encode(['message' => 'Product added to cart successfully!', 'cart' => $cart]);
    }


    function removeFromCart($id)
    {
        $cart = session()->get('cart');

        if (isset($cart[$id])) {

            unset($cart[$id]);
            $cart = $this->calculateCartTotals($cart);
            session()->put('cart', $cart);
        }

        return json_encode(['success' => 'Cart updated successfully', $cart]);
    }

    function updateCartQty(Request $request, $id)
    {


        $cart = session()->get('cart');

        if ($request->quantity) {
            $cart[$id]['quantity'] = $request->quantity;
            $cart = $this->calculateCartTotals($cart);
            session()->put('cart', $cart);
            return json_encode(['success' => 'Cart updated successfully', $cart]);
        }
    }

    private function calculateCartTotals($cart)
    {
        $cart['total'] = $this->calculateTotal($cart);
        $cart['total_with_tax'] = $this->calculateTotalWithTax($cart);
        $cart['tax'] = $this->calculateTax($cart);
        $cartCount = 0;
        foreach ($cart as $item) {
            if (is_array($item)) {
                $cartCount+= $item['quantity'];
            }
        }
        $cart['cartCount'] = $cartCount;
        return $cart;
    }


    private function calculateTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            if (is_array($item))
                $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    private function calculateTotalWithTax($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            if (is_array($item))
                $total += $item['price'] * $item['quantity'];
        }
        return $total * 1.2;
    }

    private function calculateTax($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            if (is_array($item))
                $total += $item['price'] * $item['quantity'];
        }
        return $total * 0.2;
    }
}
