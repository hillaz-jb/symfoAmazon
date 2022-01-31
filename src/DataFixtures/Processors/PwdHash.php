<?php

namespace App\DataFixtures\Processors;

use Fidry\AliceDataFixtures\ProcessorInterface;

class PwdHash implements ProcessorInterface
{

    public function preProcess(string $id, object $object): void
    {
        // TODO: Implement preProcess() method.
    }

    public function postProcess(string $id, object $object): void
    {
        // TODO: Implement postProcess() method.
    }
}