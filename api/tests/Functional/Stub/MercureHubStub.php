<?php

namespace Dungap\Tests\Functional\Stub;

use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Jwt\TokenFactoryInterface;
use Symfony\Component\Mercure\Jwt\TokenProviderInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Mercure\Jwt\StaticTokenProvider;

class MercureHubStub implements HubInterface
{
    public function __construct(
    )
    {
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
