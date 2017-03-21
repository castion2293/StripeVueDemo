<?php

namespace App\Billing;

use App\Subscription;
use Carbon\Carbon;

trait Billable
{
    /**
     * @param $stripeId
     * @return mixed
     */
    public static function byStripeId($stripeId)
    {
        return static::where('stripe_id', $stripeId)->firstOrFail();
    }

    /**
     * @param $customerId
     * @return bool
     */
    public function activate($customerId, $subscriptionId)
    {
        return $this->forceFill([
            'stripe_id' => $customerId,
            'stripe_active' => true,
            'stripe_subscription' => $subscriptionId,
            'subscription_end_at' => null
        ])->save();
    }

    /**
     * @param null $endDate
     * @return mixed
     */
    public function deactivate($endDate = null)
    {
        $endDate = $endDate ?: Carbon::now();

        return $this->forceFill([
            'stripe_active' => false,
            'subscription_end_at' => $endDate
        ])->save();
    }

    /**
     * @return Subscription
     */
    public function subscription()
    {
        return new Subscription($this);
    }

    /**
     * @return bool
     */
    public function isSubscribed()
    {
        return !! $this->stripe_active;
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}