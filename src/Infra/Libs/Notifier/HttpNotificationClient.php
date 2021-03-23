<?php

namespace App\Infra\Libs\Notifier;

use App\Domain\Model\Notification\NotificationClient;
use App\Domain\Model\Notification\NotificationClientResponse;
use App\Domain\Model\Transference;
use App\Infra\Libs\BasicHttpClient\BasicHttpClient;

class HttpNotificationClient extends BasicHttpClient implements NotificationClient
{
    public function sendTransferenceDoneSuccessfullyNotification(Transference $transference): NotificationClientResponse
    {
        [$message, $errorReason] = $this->request();

        return new NotificationClientResponse($message, $errorReason);
    }
}
