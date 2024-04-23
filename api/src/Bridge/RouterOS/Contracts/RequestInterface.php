<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\RouterOS\Contracts;

interface RequestInterface
{
    /**
     * @param array<string,mixed> $payload
     *
     * @return array<string,mixed>
     */
    public function request(string $method, string $path, array $payload = []): array;
}
