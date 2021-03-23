<?php

namespace App\Application\DataTransformer\Transference;

use App\Domain\Model\User;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

class NewTransferenceRequestDataTransformer
{
    public function createFromRaw(array $data)
    {
        $validator = Validation::createValidator();
        $constraint = new Assert\Collection([
            'payer' => [
                new Assert\Required(),
                new Assert\NotBlank(),
            ],
            'payee' => [
                new Assert\Required(),
                new Assert\NotBlank(),
                new Assert\Type(['type' => 'integer']),
                new Assert\Positive(),
            ],
            'amount' => [
                new Assert\Required(),
                new Assert\NotBlank(),
                new Assert\Type(['type' => ['float', 'integer']]),
                new Assert\Range(['min' => 0, 'max' => 800]),
            ],
        ]);

        $violations = $validator->validate($data, $constraint);

        if ($violations->count()) {
            $violation = $violations->get(0);
            $message = sprintf(
                'Invalid value to parameter %s. Details: %s',
                $violation->getPropertyPath(),
                $violation->getMessage()
            );

            throw new InvalidArgumentException($message);
        }

        if (!$data['payer'] instanceof User) {
            throw new UnauthorizedHttpException('Bearer token');
        }

        return new NewTransferenceRequestInput(
            $data['payer'],
            $data['payee'],
            $data['amount']
        );
    }
}
