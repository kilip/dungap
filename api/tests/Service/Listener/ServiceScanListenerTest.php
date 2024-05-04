<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Service\Listener;

use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\Service\ServiceScannerInterface;
use Dungap\Node\Event\NodeAddedEvent;
use Dungap\Service\Listener\ServiceScanListener;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ServiceScanListenerTest extends TestCase
{
    private MockObject|ServiceScannerInterface $scanner;
    private MockObject|NodeInterface $node;
    private NodeAddedEvent $event;
    private ServiceScanListener $listener;

    protected function setUp(): void
    {
        $this->scanner = $this->createMock(ServiceScannerInterface::class);
        $this->node = $this->createMock(NodeInterface::class);
        $this->event = new NodeAddedEvent($this->node);
        $this->listener = new ServiceScanListener($this->scanner);
    }

    public function testOnNodeAdded(): void
    {
        $this->scanner->expects($this->once())
            ->method('scan')
            ->with($this->node);
        $this->listener->onNodeAdded($this->event);
    }
}
