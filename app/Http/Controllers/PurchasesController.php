<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Customer;

class PurchasesController extends Controller
{
    public function store()
    {
        $product = Product::findOrFail(request('product'));

        try {
            $customer = Customer::create([
                'email' => request('stripeEmail'),
                'source' => request('stripeToken')
            ]);

            Charge::create([
                'customer' => $customer->id,
                'amount' => $product->price,
                'currency' => 'usd'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => $e->getMessage()], 422);
        }



        return 'All done';
    }
}
