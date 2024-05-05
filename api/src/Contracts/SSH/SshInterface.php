<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\SSH;

interface SshInterface
{
    public function disconnect(): void;

    public function execute(string $command, callable $callback = null): string;

    /**
     * @return array<int,string>
     */
    public function getLogs(): array;
}
