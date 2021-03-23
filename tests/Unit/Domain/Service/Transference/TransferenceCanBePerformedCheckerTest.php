<?php

namespace App\Tests\Unit\Domain\Service\Transference;

use App\Domain\Model\Transference\PayerHasNoEnoughMoney;
use App\Domain\Model\Transference\ShopperCannotDoTransferences;
use App\Domain\Model\Transference\TransferenceUnauthorized;
use App\Domain\Service\Transference\TransferenceCanBePerformedChecker;
use App\Entity\Transference;
use App\Entity\Wallet;
use App\Tests\Unit\BaseCodeceptionTestCase;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

class TransferenceCanBePerformedCheckerTest extends BaseCodeceptionTestCase
{
    /** @var Wallet */
    private $userWallet;

    /** @var Wallet */
    private $shopperWallet;

    /** @var TransferenceCanBePerformedChecker */
    private $service;

    protected function _before()
    {
        parent::_before();

        $this->userWallet = $this->getEntityManager()->getRepository(Wallet::class)->find(1);
        $this->shopperWallet = $this->getEntityManager()->getRepository(Wallet::class)->find(3);
        $this->service = $this->getContainer()->get('test.transference.transference-can-be-performed.success');
    }

    public function testShouldThrowsErrorWhenPayerIsShopper()
    {
        $transference = new Transference(
            null,
            Uuid::v4()->toRfc4122(),
            $this->shopperWallet,
            $this->userWallet,
            12.99,
            1,
            new DateTimeImmutable()
        );

        $this->expectException(ShopperCannotDoTransferences::class);
        $this->service->check($transference);
    }

    public function testShouldThrowsErrorWhenPayerHasNoEnoughMoney()
    {
        $transference = new Transference(
            null,
            Uuid::v4()->toRfc4122(),
            $this->userWallet,
            $this->shopperWallet,
            3000,
            1,
            new DateTimeImmutable()
        );

        $this->expectException(PayerHasNoEnoughMoney::class);
        $this->service->check($transference);
    }

    public function testShouldThrowsErrorWhenTransferenceIsExternallyUnauthorized()
    {
        $transference = new Transference(
            null,
            Uuid::v4()->toRfc4122(),
            $this->userWallet,
            $this->shopperWallet,
            300,
            1,
            new DateTimeImmutable()
        );

        $this->expectException(TransferenceUnauthorized::class);

        /** @var TransferenceCanBePerformedChecker $service */
        $service = $this->getContainer()->get('test.transference.transference-can-be-performed.unauthorized');
        $service->check($transference);
    }

    public function testAuthorizeTransference()
    {
        $transference = new Transference(
            null,
            Uuid::v4()->toRfc4122(),
            $this->userWallet,
            $this->shopperWallet,
            100,
            1,
            new DateTimeImmutable()
        );

        $this->service->check($transference);
        $this->doesNotPerformAssertions();
    }
}
