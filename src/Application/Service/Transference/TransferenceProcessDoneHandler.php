<?php

namespace App\Application\Service\Transference;

use App\Domain\Message\TransferenceProcessDone;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class TransferenceProcessDoneHandler implements MessageHandlerInterface
{
    public function __invoke(TransferenceProcessDone $message)
    {
        //send payee notifications
    }
}