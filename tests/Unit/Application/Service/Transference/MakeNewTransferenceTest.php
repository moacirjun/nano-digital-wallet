<?php

namespace App\Tests\Application\Service\Transference;

use App\Application\DataTransformer\Transference\NewTransferenceRequestInput;
use App\Application\Service\Transference\MakeNewTransference;
use App\Tests\Unit\BaseCodeceptionTestCase;

class MakeNewTransferenceTest extends BaseCodeceptionTestCase
{
    public function testExecute(\UnitTester $i)
    {
        $service = $this->getContainer()->get(MakeNewTransference::class);
        $input = new NewTransferenceRequestInput();
        $service->execute($input);
    }
}
