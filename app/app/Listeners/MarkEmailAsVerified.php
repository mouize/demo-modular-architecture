<?php

use Modules\Authentication\Events\UserVerified;

class MarkEmailAsVerified
{
    public function handle(UserVerified $event): void
    {
        $event->user->markEmailAsVerified();
    }
}
