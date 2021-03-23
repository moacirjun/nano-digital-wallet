<?php

namespace App\Tests\Application\Service\Transference;

use App\Application\DataTransformer\Transference\NewTransferenceRequestInput;
use App\Application\Service\Transference\MakeNewTransference;
use App\Domain\Model\Transference\PayerHasNoEnoughMoney;
use App\Domain\Model\Transference\ShopperCannotDoTransferences;
use App\Domain\Model\Transference\TransferenceUnauthorized;
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

    public function testShouldThrowsErrorWhenPayerIsShopper()
    {
        $shopperUser = $this->getEntityManager()
                            ->getRepository(User::class)
                            ->find(3); //shopper user id

        $input = new NewTransferenceRequestInput(
            $shopperUser,
            $this->user->getId(),
            200
        );

        $this->expectException(ShopperCannotDoTransferences::class);
        $this->service->execute($input);
    }

    public function testShouldThrowsErrorWhenPayerHasNoEnoughMoney()
    {
        $input = new NewTransferenceRequestInput(
            $this->user,
            2,
            3000
        );

        $this->expectException(PayerHasNoEnoughMoney::class);
        $this->service->execute($input);
    }

    public function testShouldThrowsErrorWhenTransferenceIsExternallyUnauthorized()
    {
        $input = new NewTransferenceRequestInput(
            $this->user,
            2,
            200
        );

        $this->expectException(TransferenceUnauthorized::class);

        /** @var MakeNewTransference $service */
        $service = $this->getContainer()->get('test.transference.make-new-transference.unauthorized');
        $service->execute($input);
    }

    public function testSuccessfullyCase()
    {
        $input = new NewTransferenceRequestInput(
            $this->user,
            2,
            200
        );

        $service = $this->getContainer()->get('test.transference.make-new-transference.success');
        $newTransference = $service->execute($input);

        $this->assertEquals(1, $newTransference->getPayerWallet()->getUser()->getId());
        $this->assertEquals(2, $newTransference->getPayeeWallet()->getUser()->getId());
        $this->assertEquals(200, $newTransference->getAmount());
    }
}
