<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Concern;

trait ContainerConcern
{
    public function setUp(): void
    {
        static::bootKernel();
    }

    /**
     * @template T
     *
     * @param class-string<T> $serviceName
     *
     * @return T|null
     */
    protected function getService(string $serviceName)
    {
        return static::getContainer()->get($serviceName);
    }
}
