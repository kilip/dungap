<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Bridge\RouterOS\Processor;

use Dungap\Bridge\RouterOS\Contracts\WakeOnLanRequestInterface;
use Dungap\Bridge\RouterOS\Processor\PowerOnProcessor;
use Dungap\Bridge\RouterOS\Request\WakeOnLanRequest;
use Dungap\Contracts\Node\FeatureInterface;
use Dungap\Contracts\Node\NodeInterface;
use Dungap\Dungap;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PowerOnProcessorTest extends TestCase
{
    private MockObject|WakeOnLanRequest $wolRequest;

    protected function setUp(): void
    {
        $this->wolRequest = $this->createMock(WakeOnLanRequestInterface::class);
    }

    public function testGetDriverName(): void
    {
        $this->assertSame(
            Dungap::RouterOsDriver,
            (new PowerOnProcessor($this->wolRequest))->getDriverName()
        );
    }

    public function testProcess(): void
    {
        $processor = new PowerOnProcessor($this->wolRequest);
        $feature = $this->createMock(FeatureInterface::class);
        $node = $this->createMock(NodeInterface::class);

        $this->wolRequest->expects($this->once())
            ->method('execute')
            ->with('mac')
        ;

        $feature->method('getNode')->willReturn($node);
        $node->method('getMac')->willReturn('mac');
        $processor->process($feature);
    }
}
