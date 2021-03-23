<?php

namespace App\Domain\Model\Notification;

use App\Entity\Transference;

interface NotificationClient
{
    public function sendTransferenceDoneSuccessfullyNotification(Transference $transference) : NotificationClientResponse;
}
