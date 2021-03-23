<?php

namespace App\Infra\Domain\Model;

use App\Domain\Model\Wallet;
use App\Domain\Model\WalletRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Wallet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wallet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wallet[]    findAll()
 * @method Wallet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineWalletRepository extends ServiceEntityRepository implements WalletRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wallet::class);
    }

    /**
     * @inheritDoc
     */
    public function findOneByUserId(int $UserId): ?Wallet
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.user = :val')
            ->setParameter('val', $UserId)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
