<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Node\Service;

use Dungap\Contracts\Node\NodeInterface;
use Dungap\Node\Service\OnlineChecker;
use PHPUnit\Framework\TestCase;

class OnlineCheckerTest extends TestCase
{
    public function testCheck(): void
    {
        $node = $this->createMock(NodeInterface::class);
        $checker = new OnlineChecker();
        $node->expects($this->once())
            ->method('getIp')
            ->willReturn('127.0.0.1');

        $result = $checker->check($node);

        $this->assertTrue($result->success);
        $this->assertIsFloat($result->latency);
    }
}
