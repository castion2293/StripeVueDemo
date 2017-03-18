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

        $customer = Customer::create([
            'email' => request('stripeEmail'),
            'source' => request('stripeToken')
        ]);

        Charge::create([
            'customer' => $customer->id,
            'amount' => $product->price,
            'currency' => 'usd'
        ]);

        return 'All done';
    }
}
