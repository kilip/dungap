<?php

namespace Dungap\Tests\Bridge\Goss\Handler;

use Dungap\Bridge\Goss\Contracts\GossFileFactoryInterface;
use Dungap\Bridge\Goss\Handler\ConfigureValidatorHandler;
use Dungap\Service\Command\ConfigureValidatorCommand;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ConfigureValidatorHandlerTest extends TestCase
{

    public function testInvoke()
    {
        $factory = $this->createMock(GossFileFactoryInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $handler = new ConfigureValidatorHandler($factory, $logger);

        $factory->expects($this->once())
            ->method('configure');
        $handler(new ConfigureValidatorCommand());
    }
}
