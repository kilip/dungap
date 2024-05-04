<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Functional\Stub;

use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Jwt\StaticTokenProvider;
use Symfony\Component\Mercure\Jwt\TokenFactoryInterface;
use Symfony\Component\Mercure\Jwt\TokenProviderInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Uid\Uuid;

class MercureHubStub implements HubInterface
{
    public function __construct(
    ) {
    }

    public function getUrl(): string
    {
        return 'http://mercure.test';
    }

    public function getPublicUrl(): string
    {
        return 'http://mercure.test';
    }

    public function getProvider(): TokenProviderInterface
    {
        return new StaticTokenProvider('testing');
    }

    public function getFactory(): ?TokenFactoryInterface
    {
        return null;
    }

    public function publish(Update $update): string
    {
        return Uuid::v7();
    }
}
