<?php

namespace App\Domain\Model\Notification;

class NotificationClientResponse
{
    /** @var string */
    private $message;

    /** @var string */
    private $failureReason;

    /**
     * NotificationClientResponse constructor.
     * @param string $message
     * @param string $failureReason
     */
    public function __construct(string $message, string $failureReason)
    {
        $this->message = $message;
        $this->failureReason = $failureReason;
    }

    public function sentSuccessfully() : bool
    {
        return $this->message === 'Enviado';
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getFailureReason(): string
    {
        return $this->failureReason;
    }
}
