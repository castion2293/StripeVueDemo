<?php
/**
 * Created by PhpStorm.
 * User: Kylie
 * Date: 3/18/2017
 * Time: 9:40 PM
 */

namespace App;

use Carbon\Carbon;
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

    public function cancel($atPeriodEnd = true)
    {
        $customer = Customer::retrieve($this->user->stripe_id);

        $subscription = $customer->cancelSubscription(['at_period_end' => $atPeriodEnd]);

        //Update our user to reflect the cancellation.
        $endDate = Carbon::createFromTimestamp($subscription->current_period_end);

        $this->user->deactivate($endDate);
    }

    public function cancelImediately()
    {
        return $this->cancel(false);
    }

    public function retrieveStripeSubscription()
    {
        return StripeSubscription::retrieve($this->user->stripe_subscription);
    }
}