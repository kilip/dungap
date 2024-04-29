<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Setting;

use Dungap\Setting\Command\NewConfigurationCommand;
use Dungap\Setting\Config;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class ConfigTest extends TestCase
{
    private string $cachePath;
    private MockObject|MessageBusInterface $bus;

    protected function setUp(): void
    {
        $this->cachePath = sys_get_temp_dir().'/dungap';
        $this->bus = $this->createMock(MessageBusInterface::class);

        $fs = new Filesystem();
        $fs->remove($this->cachePath);
    }

    public function testGetConfig(): void
    {
        $dir = __DIR__.'/fixtures';
        $configDirs = "{$dir}/dir1,{$dir}/dir2";

        $this->bus->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(NewConfigurationCommand::class))
            ->willReturn(new Envelope(new \stdClass()));

        $config = new Config(
            cachePath: $this->cachePath,
            configDirs: $configDirs,
            messageBus: $this->bus,
        );

        $this->assertNotEmpty($configs = $config->getAll());
        $this->assertArrayHasKey('devices', $configs);
        $this->assertCount(2, $config->getDevices());
    }
}
