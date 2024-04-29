<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Setting\Task;

use Dungap\Setting\Config;
use Dungap\Setting\Task\ConfigurationReloaderTask;
use PHPUnit\Framework\TestCase;

class ConfigurationReloaderTaskTest extends TestCase
{
    public function testRun(): void
    {
        $config = $this->createMock(Config::class);
        $task = new ConfigurationReloaderTask($config);

        $config->expects($this->once())
            ->method('checkConfiguration');

        $task->run();
    }
}
