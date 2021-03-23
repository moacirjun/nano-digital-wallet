<?php

namespace App\Infra\Http\Controller;

use App\Application\DataTransformer\Transference\NewTransferenceRequestDataTransformer;
use App\Application\Service\Transference\AuthorizeTransference;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TransferenceController extends AbstractFOSRestController
{
    /** @var NewTransferenceRequestDataTransformer */
    private $dataTransformer;

    /** @var AuthorizeTransference */
    private $makeNewTransference;

    public function __construct(
        NewTransferenceRequestDataTransformer $dataTransformer,
        AuthorizeTransference $makeNewTransference
    ) {
        $this->dataTransformer = $dataTransformer;
        $this->makeNewTransference = $makeNewTransference;
    }

    public function __invoke(Request $request)
    {
        $requestData = [
            'payer' => $this->getUser(),
            'payee' => $request->get('payee'),
            'amount' => $request->get('amount'),
        ];

        $input = $this->dataTransformer->createFromRaw($requestData);
        $newTransference = $this->makeNewTransference->execute($input);

        $view = $this->view(['transference' => $newTransference->getNumber()], Response::HTTP_CREATED);
        return $this->handleView($view);
    }
}
