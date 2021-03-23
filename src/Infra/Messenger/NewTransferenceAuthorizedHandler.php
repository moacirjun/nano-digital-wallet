<?php

namespace App\Infra\Messenger;

use App\Application\Service\Transference\ExecuteTransferenceProcess;
use App\Domain\Message\NewTransferenceAuthorized;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NewTransferenceAuthorizedHandler implements MessageHandlerInterface
{
    /** @var ExecuteTransferenceProcess */
    private $executeTransferenceProcess;

    public function __construct(ExecuteTransferenceProcess $executeTransferenceProcess)
    {
        $this->executeTransferenceProcess = $executeTransferenceProcess;
    }

    public function __invoke(NewTransferenceAuthorized $message)
    {
        $this->executeTransferenceProcess->execute($message->getTransference());
    }
}
