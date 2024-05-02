<?php

namespace Dungap\Tests\Service\Task;

use Dungap\Bridge\Goss\Config\GossFile;
use Dungap\Bridge\Goss\Contracts\GossFileFactoryInterface;
use Dungap\Bridge\Goss\Contracts\GossFileInterface;
use Dungap\Service\Task\ValidateTask;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class ValidateTaskTest extends TestCase
{
    private MockObject|GossFileFactoryInterface $fileFactory;
    private MockObject|MessageBusInterface $messageBus;
    private MockObject|GossFile $gossFile;
    private \Dungap\Service\Task\ValidateTask $validate;

    protected function setUp(): void
    {
        $this->fileFactory= $this->createMock(GossFileFactoryInterface::class);
        $this->messageBus= $this->createMock(MessageBusInterface::class);
        $this->gossFile = $this->createMock(GossFileInterface::class);
        $this->validate = new \Dungap\Service\Task\ValidateTask($this->fileFactory, $this->messageBus);
    }
    public function testPreRun()
    {
        $this->fileFactory->expects($this->once())
            ->method('getFile')
            ->willReturn($this->gossFile);
        ;
        $this->gossFile->expects($this->once())
            ->method('getFileName')
            ->willReturn(__DIR__.'/not-exists');
        $this->fileFactory->expects($this->once())
            ->method('configure');

        $this->validate->preRun();
    }

    public function testRun()
    {
        $this->messageBus->expects($this->once())
            ->method('dispatch')
            ->willReturn(new Envelope(new \stdClass()));

        $this->validate->run();
    }
}
