<?php

namespace App\Tests\Application\Service\Transference;

use App\Application\DataTransformer\Transference\NewTransferenceRequestInput;
use App\Application\Service\Transference\MakeNewTransference;
use App\Entity\User;
use App\Tests\Unit\BaseCodeceptionTestCase;
use Doctrine\ORM\EntityNotFoundException;

class MakeNewTransferenceTest extends BaseCodeceptionTestCase
{
    /** @var User */
    private $user;

    /** @var MakeNewTransference */
    private $service;

    protected function _before()
    {
        parent::_before();

        $this->user = $this->getEntityManager()->getRepository(User::class)->find(1);
        $this->service = $this->getContainer()->get(MakeNewTransference::class);
    }

    public function testShouldThrowsErrorWhenPayeeIsNotFound()
    {
        $input = new NewTransferenceRequestInput(
            $this->user,
            999, //Nonexistent userId
            200
        );

        $this->expectException(EntityNotFoundException::class);
        $this->service->execute($input);
    }

    public function testSuccessfullyCase()
    {
        $input = new NewTransferenceRequestInput(
            $this->user,
            2,
            200
        );

        $newTransference = $this->service->execute($input);

        $this->assertEquals(1, $newTransference->getPayerWallet()->getUser()->getId());
        $this->assertEquals(2, $newTransference->getPayeeWallet()->getUser()->getId());
        $this->assertEquals(200, $newTransference->getAmount());
    }
}
