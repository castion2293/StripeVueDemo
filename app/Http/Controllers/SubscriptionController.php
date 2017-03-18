<?php

namespace App\Http\Controllers;

use App\Plan;
use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Customer;

class SubscriptionController extends Controller
{
    public function store()
    {
        $plan = Plan::findOrFail(request('plan'));

        try {
            $customer = Customer::create([
                'email' => request('stripeEmail'),
                'source' => request('stripeToken'),
                'plan' => $plan->name
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => $e->getMessage()], 422);
        }

        return 'All done';
    }
}
