<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\API;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Dungap\Tests\Factory\DeviceFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class DeviceTest extends ApiTestCase
{
    // use ResetDatabase, Factories;
    use Factories;
    use ResetDatabase;

    public function testGetCollection(): void
    {
        $client = static::createClient();
        DeviceFactory::createMany(100);

        $response = $client->request('GET', '/devices');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/contexts/device',
            '@id' => '/devices',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 100,
            'hydra:view' => [
                '@id' => '/devices?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/devices?page=1',
                'hydra:last' => '/devices?page=4',
                'hydra:next' => '/devices?page=2',
            ],
        ]);
    }
}
