<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\Goss\Task;

use Dungap\Contracts\Task\TaskInterface;
use Dungap\Task\TaskTrait;

class Validate implements TaskInterface
{
    use TaskTrait;

    public function __construct(
    ) {
    }

    public function preRun(): void
    {
        // TODO: Implement preRun() method.
    }

    public function run(): void
    {
        // TODO: Implement run() method.
    }
}
