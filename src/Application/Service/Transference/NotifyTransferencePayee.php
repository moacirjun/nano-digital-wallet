<?php

namespace App\Application\Service\Transference;

use App\Domain\Model\Notification\ErrorSendingTransferencePayeeNotification;
use App\Domain\Model\Notification\NotificationClient;
use App\Entity\Transference;
use Psr\Log\LoggerInterface;

class NotifyTransferencePayee
{
    /** @var NotificationClient */
    private $notificationsClient;

    /** @var $logger */
    private $logger;

    public function __construct(NotificationClient $notificationsClient, LoggerInterface $logger)
    {
        $this->notificationsClient = $notificationsClient;
        $this->logger = $logger;
    }

    public function execute(Transference $transference)
    {
        $response = $this->notificationsClient->sendTransferenceDoneSuccessfullyNotification($transference);

        if ($response->sentSuccessfully()) {
            return;
        }

        $this->logger->error('[TRANSFERENCE] - Error sending transference payee notification', [
            'transference' => $transference->getNumber(),
            'reason' => $response->getFailureReason(),
        ]);

        throw new ErrorSendingTransferencePayeeNotification($response->getFailureReason());
    }
}
