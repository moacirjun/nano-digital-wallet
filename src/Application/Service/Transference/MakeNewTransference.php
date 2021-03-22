<?php

namespace App\Application\Service\Transference;

use App\Application\DataTransformer\Transference\NewTransferenceRequestInput;
use App\Domain\Message\NewTransferenceAuthorized;
use App\Domain\Model\Transference\TransferenceUnauthorized;
use App\Domain\Service\Transference\TransferenceFactory;
use App\Domain\Service\Transference\TransferenceCanBePerformedChecker;
use App\Domain\Service\User\FindUserById;
use App\Entity\Transference;
use App\Entity\User;
use Doctrine\ORM\EntityNotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MakeNewTransference
{
    /** @var FindUserById */
    private $findUserService;

    /** @var TransferenceCanBePerformedChecker  */
    private $transferenceCanBePerformedChecker;

    /** @var LoggerInterface */
    private $logger;

    /** @var TransferenceFactory */
    private $transferenceFactory;

    /** @var MessageBusInterface */
    private $bus;

    public function __construct(
        FindUserById $findUserService,
        TransferenceCanBePerformedChecker $transferenceCanBePerformedChecker,
        LoggerInterface $logger,
        TransferenceFactory $transferenceFactory,
        MessageBusInterface $bus
    ) {
        $this->findUserService = $findUserService;
        $this->transferenceCanBePerformedChecker = $transferenceCanBePerformedChecker;
        $this->logger = $logger;
        $this->transferenceFactory = $transferenceFactory;
        $this->bus = $bus;
    }

    /**
     * @param NewTransferenceRequestInput $input
     * @return Transference
     * @throws EntityNotFoundException
     */
    public function execute(NewTransferenceRequestInput $input) : Transference
    {
        $payee = $this->findPayee($input->getPayeeId());

        $transference = $this->transferenceFactory->makeFromUsers($input->getPayer(), $payee, $input->getAmount());

        try {
            $this->transferenceCanBePerformedChecker->check($transference);
        } catch (TransferenceUnauthorized $exception) {
            $this->logger->info(
                '[TRANSFERENCE] Transference unauthorized',
                [
                    'payer' => $transference->getPayerWallet()->getId(),
                    'payee' => $transference->getPayeeWallet()->getId(),
                    'transference' => $transference->getNumber(),
                    'exception' => $exception,
                ]
            );

            throw $exception;
        }

        $this->bus->dispatch(new NewTransferenceAuthorized($transference));

        return $transference;
    }

    /**
     * @param int $userId
     * @return User
     * @throws EntityNotFoundException
     */
    private function findPayee(int $userId)
    {
        try {
            return $this->findUserService->find($userId);
        } catch (EntityNotFoundException $e) {
            $this->logger->info('[TRANSFERENCE] Transference to a invalid payee', [
                'payer' => $userId,
                'exception' => $e,
            ]);

            throw $e;
        }
    }
}