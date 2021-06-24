<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\CreateOrderRequest;

use App\Models\Product;

class OrderController extends Controller
{
    public function order(CreateOrderRequest $request)
    {
        $product = Product::find($request->product_id);

        if ( !$product ) {
            return response()->json([
                'message' => 'Product not found.'
            ], 400);
        }

        if ( $product->available_stock < $request->quantity ) {
            return response()->json([
                'message' => 'Failed to order this product due to unavailability of the stock.'
            ], 400);
        }

        $product->available_stock -= $request->quantity;
        $product->save();

        return response()->json([
            'message' => 'You have successfully ordered this product.'
        ], 201);
    }
}
