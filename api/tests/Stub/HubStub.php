<?php

namespace Dungap\Tests\Stub;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Jwt\LcobucciFactory;
use Symfony\Component\Mercure\Jwt\StaticTokenProvider;
use Symfony\Component\Mercure\Jwt\TokenFactoryInterface;
use Symfony\Component\Mercure\Jwt\TokenProviderInterface;
use Symfony\Component\Mercure\Update;

class HubStub implements HubInterface
{
    public function publish(Update $update): string
    {
        return 'id';
    }

    public function getUrl(): string
    {
        return 'http://internal/.well-known/mercure';
    }

    public function getPublicUrl(): string
    {
        return 'http://internal/.well-known/mercure';
    }

    public function getProvider(): TokenProviderInterface
    {
        return new StaticTokenProvider(getenv('MERCURE_JWT_SECRET'));
    }

    public function getFactory(): ?TokenFactoryInterface
    {
        return new LcobucciFactory(getenv('MERCURE_JWT_SECRET'));
    }
}