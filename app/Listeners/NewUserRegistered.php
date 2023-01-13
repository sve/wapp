<?php

namespace App\Listeners;

use App\Jobs\User\NewUserRegisteredJob;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewUserRegistered implements ShouldQueue
{
    /**
     * Reward a gift card to the customer.
     *
     * @param Registered $event
     * @return void
     */
    public function handle(Registered $event): void
    {
        NewUserRegisteredJob::dispatch($event->user);
    }
}
