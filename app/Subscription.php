<?php
/**
 * Created by PhpStorm.
 * User: Kylie
 * Date: 3/18/2017
 * Time: 9:40 PM
 */

namespace App;

use Stripe\Customer;
use Stripe\Subscription as StripeSubscription;

class Subscription
{
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * @param \App\Plan $plan
     * @param $token
     */
    public function create($plan, $token)
    {
        $customer = Customer::create([
            'email' => $this->user->email,
            'source' => $token,
            'plan' => $plan->name
        ]);

        $subscriptionId = $customer->subscriptions->data[0]->id;

        $this->user->activate($customer->id, $subscriptionId);
    }

    public function retrieve()
    {
        return StripeSubscription::retrieve($this->user->stripe_subscription);
    }
}