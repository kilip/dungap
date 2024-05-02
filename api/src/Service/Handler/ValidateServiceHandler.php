<?php

namespace Dungap\Service\Handler;

use Dungap\Service\Command\ValidateServiceCommand;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ValidateServiceHandler
{
    public function __invoke(ValidateServiceCommand $command): void
    {

    }
}
