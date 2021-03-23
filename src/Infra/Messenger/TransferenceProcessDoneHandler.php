<?php

namespace App\Infra\Messenger;

use App\Application\Service\Transference\NotifyTransferencePayee;
use App\Domain\Message\TransferenceProcessDone;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class TransferenceProcessDoneHandler implements MessageHandlerInterface
{
    /** @var NotifyTransferencePayee */
    private $notifyTransferencePayee;

    public function __construct(NotifyTransferencePayee $notifierService)
    {
        $this->notifyTransferencePayee = $notifierService;
    }

    public function __invoke(TransferenceProcessDone $message)
    {
        $this->notifyTransferencePayee->execute($message->getTransference());
    }
}
