<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Setting\Task;

use Dungap\Contracts\Task\TaskInterface;
use Dungap\Setting\Config;
use Dungap\Task\TaskTrait;

class ConfigurationReloaderTask implements TaskInterface
{
    use TaskTrait;

    public function __construct(
        private Config $config
    ) {
    }

    /**
     * @codeCoverageIgnore
     */
    public function preRun(): void
    {
    }

    public function run(): void
    {
        $this->config->checkConfiguration();
    }
}
