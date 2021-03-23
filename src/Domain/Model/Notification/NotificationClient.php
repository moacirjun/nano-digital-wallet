<?php

namespace App\Domain\Model\Notification;

use App\Domain\Model\Transference;

interface NotificationClient
{
    public function sendTransferenceDoneSuccessfullyNotification(Transference $transference) : NotificationClientResponse;
}
