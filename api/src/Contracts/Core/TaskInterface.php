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

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('dungap.task')]
interface TaskInterface
{
    public function setLastRun(\DateTimeImmutable $carbon): void;

    public function getLastRun(): ?\DateTimeImmutable;

    public function getInterval(): int;

    public function run(): void;
}
