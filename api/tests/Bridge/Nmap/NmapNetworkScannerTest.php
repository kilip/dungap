<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Bridge\Nmap;

use Dungap\Bridge\Nmap\NmapResultParser;
use Dungap\Bridge\Nmap\NmapScannerDeviceScanner;
use Dungap\Device\Command\ScanDeviceCommand;
use Dungap\Device\DTO\ResultDevice;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\Process;

class NmapNetworkScannerTest extends TestCase
{
    private MockObject|Process $process;
    private MockObject|LoggerInterface $logger;
    private ResultDevice $resultDevice;
    private NmapScannerDeviceScanner $scanner;

    public function setUp(): void
    {
        $this->process = $this->createMock(Process::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $resultParser = $this->createMock(NmapResultParser::class);
        $this->resultDevice = new ResultDevice('10.0.0.1');

        $resultParser
            ->method('parse')
            ->willReturn([$this->resultDevice]);

        $this->scanner = new NmapScannerDeviceScanner(
            resultParser: $resultParser,
            logger: $this->logger,
            process: $this->process
        );
    }

    public function testScan(): void
    {
        $this->process->expects($this->once())
            ->method('start');
        $this->process->expects($this->once())
            ->method('wait')
            ->with([$this->scanner, 'onProcess']);

        $results = $this->scanner->scan(new ScanDeviceCommand(['10.0.0.0/24']));
        $this->assertSame([$this->resultDevice], $results);
    }

    public function testOnProcess(): void
    {
        $this->logger->expects($this->once())
            ->method('notice')
            ->with('nmap.err>> buffer');

        $this->scanner->onProcess(Process::ERR, 'buffer');
    }
}
