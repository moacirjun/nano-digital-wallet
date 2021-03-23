<?php

namespace App\Domain\Service\Transference;

use App\Domain\Model\Transference\ErrorProcessingNewTransference;
use App\Domain\Model\Transference\PayerHasNoEnoughMoney;
use App\Domain\Model\Transference;
use Doctrine\ORM\EntityManagerInterface;
use Throwable;

/**
 * Process all database changes to make transference
 */
class newTransferenceDatabaseManager
{
    /** @var EntityManagerInterface */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Transference $transference
     * @throws ErrorProcessingNewTransference
     */
    public function process(Transference $transference)
    {
        $payerWallet = $transference->getPayerWallet();
        $payeeWallet = $transference->getPayeeWallet();

        $this->manager->refresh($payerWallet);
        $this->manager->refresh($payeeWallet);

        if ($payerWallet->getOnHand() < $transference->getAmount()) {
            throw new PayerHasNoEnoughMoney();
        }

        $this->manager->beginTransaction();

        try {
            $payerWallet->setOnHand($payerWallet->getOnHand() - $transference->getAmount());
            $payeeWallet->setOnHand($payeeWallet->getOnHand() + $transference->getAmount());

            $this->manager->persist($payerWallet);
            $this->manager->persist($transference);
            $this->manager->flush();

            $this->manager->commit();
        } catch (Throwable $exception) {
            $this->manager->rollback();

            throw new ErrorProcessingNewTransference($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
