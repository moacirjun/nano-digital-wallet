<?php


namespace App\Domain\Service\Transference;


use App\Domain\Model\Transference\ExternalAuthorizerRpc;
use App\Domain\Model\Transference\PayerHasNoEnoughMoney;
use App\Domain\Model\Transference\ShopperCannotDoTransferences;
use App\Domain\Model\Transference\TransferenceUnauthorized;
use App\Domain\Model\Transference;

class TransferenceCanBePerformedChecker
{
    /** @var ExternalAuthorizerRpc */
    private $authorizerRpc;

    public function __construct(ExternalAuthorizerRpc $authorizerRpc)
    {
        $this->authorizerRpc = $authorizerRpc;
    }

    /**
     * @param Transference $transference
     * @throws ShopperCannotDoTransferences|PayerHasNoEnoughMoney|TransferenceUnauthorized
     */
    public function check(Transference $transference) : void
    {
        $payerWallet = $transference->getPayerWallet();
        $payer = $payerWallet->getUser();
        if ($payer->getType() !== 1) {
            throw new ShopperCannotDoTransferences();
        }

        if ($payerWallet->getOnHand() < $transference->getAmount()) {
            throw new PayerHasNoEnoughMoney();
        }

        $authorizerResponse = $this->authorizerRpc->checkTransferenceAuthorization($transference);

        if (!$authorizerResponse->isAuthorized()) {
            throw new TransferenceUnauthorized($authorizerResponse->getFailureReason());
        };
    }
}
