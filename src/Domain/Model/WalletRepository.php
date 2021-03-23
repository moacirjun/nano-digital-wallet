<?php

namespace App\Domain\Model;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ObjectRepository;

interface WalletRepository extends ObjectRepository
{
    /**
     * @param $UserId
     * @return Wallet|null
     * @throws NonUniqueResultException
     */
    public function findOneByUserId(int $UserId): ?Wallet;
}
