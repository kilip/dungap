<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Tests\Factory;

use Dungap\Device\Entity\Device;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Device>
 *
 * @method        Device|Proxy     create(array|callable $attributes = [])
 * @method static Device|Proxy     createOne(array $attributes = [])
 * @method static Device[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Device[]|Proxy[] createSequence(iterable|callable $sequence)
 *
 * @phpstan-method        Proxy<Device> create(array|callable $attributes = [])
 * @phpstan-method static Proxy<Device> createOne(array $attributes = [])
 * @phpstan-method static list<Proxy<Device>> createMany(int $number, array|callable $attributes = [])
 * @phpstan-method static list<Proxy<Device>> createSequence(iterable|callable $sequence)
 */
final class DeviceFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        $data = [
            'name' => self::faker()->unique()->word(),
            'hostname' => self::faker()->unique()->domainWord(),
            'ipAddress' => self::faker()->unique()->ipv4(),
            'macAddress' => self::faker()->unique()->macAddress(),
            'draft' => self::faker()->randomElement([true, false]),
        ];

        return $data;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this;
    }

    protected static function getClass(): string
    {
        return Device::class;
    }
}
