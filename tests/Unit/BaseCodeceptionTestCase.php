<?php

namespace App\Tests\Unit;

use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BaseCodeceptionTestCase extends Unit
{
    protected function getContainer(): ContainerInterface
    {
        return $this->getModule('Symfony')->_getContainer();
    }

    protected function getEntityManager() : EntityManagerInterface
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
