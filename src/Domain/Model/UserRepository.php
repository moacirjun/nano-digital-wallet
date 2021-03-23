<?php

namespace App\Domain\Model;

use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

interface UserRepository extends ObjectRepository, PasswordUpgraderInterface
{
}
