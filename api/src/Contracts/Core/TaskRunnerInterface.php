<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\Core;

use Symfony\Component\Console\Output\OutputInterface;

interface TaskRunnerInterface
{
    public function run(OutputInterface $output): void;
}
