<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Bridge\SSH\Processor;

use Dungap\Contracts\Node\FeatureInterface;
use Dungap\Contracts\Node\FeatureProcessorInterface;
use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\SSH\SshFactoryInterface;
use Dungap\Contracts\SSH\SshInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;

trait TestSshProcessorConcern
{
    private MockObject|SshFactoryInterface $sshFactory;
    private MockObject|FeatureInterface $feature;
    private MockObject|SshInterface $ssh;
    private MockObject|LoggerInterface $logger;

    private FeatureProcessorInterface $processor;

    protected function setUp(): void
    {
        $this->sshFactory = $this->createMock(SshFactoryInterface::class);
        $this->ssh = $this->createMock(SshInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->feature = $this->createMock(FeatureInterface::class);
        $node = $this->createMock(NodeInterface::class);

        $processorClass = $this->getProcessorClassName();
        $this->processor = new $processorClass(
            $this->sshFactory,
            $this->logger
        );

        $this->feature->method('getNode')
            ->willReturn($node);
    }

    abstract protected function getProcessorClassName(): string;

    abstract protected function getSshCommand(): string;

    public function testProcess(): void
    {
        $command = $this->getSshCommand();
        $this->sshFactory->expects($this->exactly(2))
            ->method('createSshClient')
            ->willReturn($this->ssh);

        $this->ssh->expects($this->exactly(2))
            ->method('execute')
            ->with($command)
            ->willReturnCallback(function () {
                static $counter = 1;

                if ($counter > 1) {
                    throw new \Exception('test');
                }
                ++$counter;

                return 'test';
            })
        ;

        $this->processor->process($this->feature);

        $this->logger->expects($this->once())
            ->method('error');
        $this->processor->process($this->feature);
    }
}
