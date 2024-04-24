<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Task;

trait TaskTrait
{
    protected ?\DateTimeImmutable $lastRun = null;

    protected int $interval = 30;

    public function setLastRun(\DateTimeImmutable $carbon): void
    {
        $this->lastRun = $carbon;
    }

    public function getLastRun(): ?\DateTimeImmutable
    {
        return $this->lastRun;
    }

    public function getInterval(): int
    {
        return $this->interval;
    }
}
