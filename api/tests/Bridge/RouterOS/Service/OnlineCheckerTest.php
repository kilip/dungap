<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Bridge\RouterOS\Service;

use Dungap\Bridge\RouterOS\Contracts\RequestInterface;
use Dungap\Bridge\RouterOS\Service\OnlineChecker;
use PHPUnit\Framework\TestCase;

class OnlineCheckerTest extends TestCase
{
    public function testRun(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $json = json_decode(file_get_contents(__DIR__.'/../fixtures/online-checker-01.json'), true);
        $checker = new OnlineChecker(
            $request
        );
        $request->expects($this->once())
            ->method('request')
            ->with('GET', '/ip/dhcp-server/lease')
            ->willReturn($json);

        $results = $checker->run();

        $this->assertCount(2, $results);

        $this->assertTrue($results[0]->online);
        $this->assertFalse($results[1]->online);
    }
}
