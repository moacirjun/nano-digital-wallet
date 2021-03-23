<?php

namespace App\Application\Service\Transference;

use App\Domain\Message\TransferenceProcessDone;
use App\Domain\Model\Transference\ErrorProcessingNewTransference;
use App\Domain\Service\Transference\newTransferenceDatabaseManager;
use App\Entity\Transference;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ExecuteTransferenceProcess
{
    /** @var newTransferenceDatabaseManager */
    private $transferenceDatabaseManager;

    /** @var LoggerInterface */
    private $logger;

    /** @var MessageBusInterface */
    private $bus;

    public function __construct(
        newTransferenceDatabaseManager $transferenceDatabaseManager,
        LoggerInterface $logger,
        MessageBusInterface $bus
    ) {
        $this->transferenceDatabaseManager = $transferenceDatabaseManager;
        $this->logger = $logger;
        $this->bus = $bus;
    }

    public function execute(Transference $transference)
    {
        try {
            $this->transferenceDatabaseManager->process($transference);
        } catch (ErrorProcessingNewTransference $exception) {
            $this->logger->error('[TRANSFERENCE] Error saving transference database changes', [
                'payer' => $transference->getPayerWallet()->getId(),
                'payee' => $transference->getPayeeWallet()->getId(),
                'transference' => $transference->getNumber(),
                'exception' => $exception,
            ]);

            throw $exception;
        }

        $this->bus->dispatch(new TransferenceProcessDone($transference));
    }
}
