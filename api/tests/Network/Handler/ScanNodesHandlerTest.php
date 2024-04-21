<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Network\Handler;

use Dungap\Contracts\Network\NetworkScannerInterface;
use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\Node\NodeRepositoryInterface;
use Dungap\Network\Command\ScanNodesCommand;
use Dungap\Network\Handler\ScanNodesHandler;
use Dungap\Network\ResultNode;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ScanNodesHandlerTest extends TestCase
{
    private MockObject|NetworkScannerInterface $scanner;
    private MockObject|ResultNode $result;
    private MockObject|NodeRepositoryInterface $nodeRepository;
    private ScanNodesHandler $handler;

    protected function setUp(): void
    {
        $this->scanner = $this->createMock(NetworkScannerInterface::class);
        $this->result = new ResultNode(
            ipAddress: 'ip',
            hostname: 'hostname',
            vendor: 'vendor',
            macAddress: 'mac'
        );
        $this->nodeRepository = $this->createMock(NodeRepositoryInterface::class);

        $this->scanner->expects($this->once())
            ->method('scan')
            ->with(['target'])
            ->willReturn([$this->result]);

        $this->handler = new ScanNodesHandler($this->scanner, $this->nodeRepository);
    }

    public function testInvoke(): void
    {
        $handler = $this->handler;

        $this->nodeRepository->expects($this->once())
            ->method('store');

        $handler(new ScanNodesCommand(['target']));
    }

    /**
     * @dataProvider getTestInvokeWithExistingNode
     */
    public function testInvokeWithExistingNode(string $method, string $value): void
    {
        $handler = $this->handler;
        $node = $this->createMock(NodeInterface::class);
        $this->nodeRepository->expects($this->once())
            ->method($method)
            ->with($this->equalTo($value))
            ->willReturn($node);

        $handler(new ScanNodesCommand(['target']));
    }

    /**
     * @return array<int,array<int,string>>
     */
    public function getTestInvokeWithExistingNode(): array
    {
        return [
            ['findByMacAddress', 'mac'],
            ['findByIpAddress', 'ip'],
            ['findByHostname', 'hostname'],
        ];
    }
}
