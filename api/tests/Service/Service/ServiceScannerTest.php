<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Service\Service;

use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\Service\ServiceReportInterface;
use Dungap\Contracts\Service\ServiceValidatorInterface;
use Dungap\Dungap;
use Dungap\Service\Event\ServiceScannedEvent;
use Dungap\Service\Service\ServiceScanner;
use Dungap\Service\ServiceException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ServiceScannerTest extends TestCase
{
    private MockObject|EventDispatcherInterface $dispatcher;
    private MockObject|NodeInterface $node;

    private ServiceScanner $scanner;

    public function setUp(): void
    {
        $validator = $this->createMock(ServiceValidatorInterface::class);
        $report = $this->createMock(ServiceReportInterface::class);
        $this->dispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->node = $this->createMock(NodeInterface::class);

        $this->scanner = new ServiceScanner(
            $validator,
            $this->dispatcher,
            [
                ['port' => 80, 'timeout' => 500]
            ]
        );

        $this->node->method('getIp')
            ->willReturn('127.0.0.1');

        $validator->method('validate')
            ->with($this->node, 80, 500)
            ->willReturn($report);
    }

    public function testScan(): void
    {
        $this->dispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(ServiceScannedEvent::class), Dungap::OnServiceScanned);
        $this->scanner->scan($this->node);
    }

    public function testScanWithError(): void
    {
        $this->dispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(ServiceScannedEvent::class), Dungap::OnServiceScanned)
            ->willThrowException(new \Exception('test'));

        $this->expectException(ServiceException::class);

        $this->scanner->scan($this->node);
    }
}
