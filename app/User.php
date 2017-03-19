<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'stripe_id', 'stripe_active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @param $customerId
     * @return bool
     */
    public function activate($customerId = null)
    {
        return $this->forceFill([
            'stripe_id' => $customerId ?: $this->stripe_id,
            'stripe_active' => true,
            'subscription_end_at' => null
        ])->save();
    }

    /**
     * @return bool
     */
    public function deactivate()
    {
        return $this->forceFill([
            'stripe_active' => false,
            'subscription_end_at' => Carbon::now()
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
}
