<?php


namespace App\Domain\Service\User;


use App\Domain\Model\User;
use App\Domain\Model\UserRepository;
use Doctrine\ORM\EntityNotFoundException;

class FindUserById
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function find(int $userId) : User
    {
        $user = $this->userRepository->find($userId);

        if (!$user instanceof User) {
            throw new EntityNotFoundException(sprintf('The user [%s] was not found', $userId));
        }

        return $user;
    }
}
