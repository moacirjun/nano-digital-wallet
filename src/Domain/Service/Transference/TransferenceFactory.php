<?php

namespace App\Domain\Service\Transference;

use App\Domain\Model\Transference;
use App\Domain\Model\User;
use App\Domain\Model\Wallet;
use App\Domain\Model\WalletRepository;
use DateTimeImmutable;
use Doctrine\ORM\NonUniqueResultException;
use InvalidArgumentException;
use Symfony\Component\Uid\Uuid;

class TransferenceFactory
{
    /** @var WalletRepository */
    private $walletRepository;

    /**
     * TransferenceAuthorizedFactory constructor.
     * @param WalletRepository $walletRepository
     */
    public function __construct(WalletRepository $walletRepository)
    {
        $this->walletRepository = $walletRepository;
    }

    public function makeFromUsers(User $payer, User $payee, float $amount) : Transference
    {
        $payerWallet = $this->findUserWallet($payer);
        $payeeWallet = $this->findUserWallet($payee);

        return $this->make($payerWallet, $payeeWallet, $amount);
    }

    public function make(Wallet $payerWallet, Wallet $payeeWallet, float $amount) : Transference
    {
        $uuid = Uuid::v4();

        return new Transference(
            null,
            $uuid->toRfc4122(),
            $payerWallet,
            $payeeWallet,
            $amount,
            1,
            new DateTimeImmutable()
        );
    }

    /**
     * @param User $user
     * @return Wallet
     * @throws InvalidArgumentException
     */
    private function findUserWallet(User $user) : Wallet
    {
        try {
            $wallet = $this->walletRepository->findOneByUserId($user->getId());
        } catch (NonUniqueResultException $e) {
            throw new InvalidArgumentException(sprintf('Error finding user[%s] wallet.', $user->getId()));
        }

        if (!$wallet instanceof Wallet) {
            throw new InvalidArgumentException(sprintf('Error finding user[%s] wallet.', $user->getId()));
        }

        return $wallet;
    }
}
