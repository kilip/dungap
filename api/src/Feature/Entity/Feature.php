<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Feature\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Feature\FeatureInterface;
use Dungap\Feature\Repository\FeatureRepository;
use Faker\Core\Uuid;

#[ORM\Entity(repositoryClass: FeatureRepository::class)]
class Feature implements FeatureInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ManyToOne(targetEntity: DeviceInterface::class)]
    private DeviceInterface $device;

    #[ORM\Column(type: 'string', length: 20)]
    private string $driver;

    #[ORM\Column(type: 'string', length: 20)]
    private string $feature;

    /**
     * @var array<string,mixed>
     */
    #[ORM\Column(type: 'json', nullable: true)]
    private array $setting = [];

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getDevice(): DeviceInterface
    {
        return $this->device;
    }

    public function setDevice(DeviceInterface $device): void
    {
        $this->device = $device;
    }

    public function getFeature(): string
    {
        return $this->feature;
    }

    public function setFeature(string $feature): void
    {
        $this->feature = $feature;
    }

    public function getSetting(): array
    {
        return $this->setting;
    }

    public function setSetting(array $setting): void
    {
        $this->setting = $setting;
    }

    public function getDriver(): string
    {
        return $this->driver;
    }

    public function setDriver(string $driver): void
    {
        $this->driver = $driver;
    }
}
