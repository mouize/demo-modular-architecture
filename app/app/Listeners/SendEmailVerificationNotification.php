<?php

namespace App\Listeners;

use Modules\Authentication\Events\UserRegistered;

class SendEmailVerificationNotification
{
    public function handle(UserRegistered $event): void
    {
        $event->user->sendEmailVerificationNotification();
    }
}
