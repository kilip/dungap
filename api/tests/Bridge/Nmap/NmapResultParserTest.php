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

use Dungap\Bridge\Nmap\NmapException;
use Dungap\Bridge\Nmap\NmapResultParser;
use PHPUnit\Framework\TestCase;

class NmapResultParserTest extends TestCase
{
    public function testParseWithNonExistingFiles(): void
    {
        $parser = new NmapResultParser();

        $this->expectException(NmapException::class);

        $parser->parse('not_exists.xml');
    }

    /**
     * @dataProvider getTestParse
     */
    public function testParse(
        string $filename,
        int $expectedNumHosts,
        int $expectedOnlineIps
    ): void {
        $parser = new NmapResultParser();
        $hosts = $parser->parse($filename);

        $this->assertCount($expectedNumHosts, $hosts);
        $this->assertCount($expectedOnlineIps, $parser->getOnlineIps());
    }

    /**
     * @return array<int,array<int,string>>
     */
    public function getTestParse(): array
    {
        return [
            [__DIR__.'/fixtures/result-01.xml', 2, 2],
            [__DIR__.'/fixtures/result-02.xml', 1, 1],
            [__DIR__.'/fixtures/result-03.xml', 4, 4],
        ];
    }
}
