<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Device\Entity;

use ApiPlatform\Doctrine\Odm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Doctrine\ORM\Mapping as ORM;
use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Device\EnumDeviceFeature;
use Dungap\Device\Controller\PowerOffAction;
use Dungap\Device\Controller\PowerOnAction;
use Dungap\Device\Controller\ScanDeviceAction;
use Dungap\Device\Repository\DeviceRepository;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'device',
    operations: [
        new GetCollection(),
        new Post(
            security: 'is_granted("ROLE_ADMIN")'
        ),
        new Get(),
        new Put(
            security: 'is_granted("ROLE_ADMIN")'
        ),
        new Patch(
            security: 'is_granted("ROLE_ADMIN")'
        ),
        new Delete(
            security: 'is_granted("ROLE_ADMIN")'
        ),
        new Post(
            uriTemplate: '/devices/scan',
            controller: ScanDeviceAction::class,
            description: 'Scan available devices on network',
            read: false,
            name: 'api_device_scan',
            security: 'is_granted("ROLE_ADMIN")'
        ),
        new Get(
            uriTemplate: '/devices/{id}/power-on',
            controller: PowerOnAction::class,
            write: false,
            name: 'api_device_power_on',
            security: 'is_granted("ROLE_ADMIN")'
        ),
        new Get(
            uriTemplate: '/devices/{id}/power-off',
            controller: PowerOffAction::class,
            write: false,
            name: 'api_device_power_off',
            security: 'is_granted("ROLE_ADMIN")'
        ),
    ],
    mercure: true
)]
#[ORM\Entity(repositoryClass: DeviceRepository::class)]
class Device implements DeviceInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private ?Uuid $id = null;

    #[ApiFilter(OrderFilter::class)]
    #[ORM\Column(type: 'string', unique: true, nullable: true)]
    private ?string $nickname = null;

    #[ApiFilter(OrderFilter::class)]
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $hostname = null;

    #[ORM\Column(type: 'string', unique: true, nullable: true)]
    private ?string $ipAddress = null;

    #[ORM\Column(type: 'string', unique: true, nullable: true)]
    private ?string $macAddress = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $netVendor = null;

    #[ORM\Column(type: 'boolean')]
    private bool $draft = false;

    #[ORM\Column(type: 'boolean')]
    private bool $online = false;

    #[ORM\Column(type: 'datetimetz_immutable', nullable: true)]
    private ?\DateTimeImmutable $uptime = null;

    /**
     * @var array<int,string>
     */
    #[ORM\Column(type: 'json')]
    private array $features = [];

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function addFeature(EnumDeviceFeature $feature): DeviceInterface
    {
        if (!$this->hasFeature($feature)) {
            $this->features[] = $feature->value;
        }

        return $this;
    }

    public function getFeatures(): array
    {
        return $this->features;
    }

    public function setFeatures(array $features): DeviceInterface
    {
        $this->features = $features;

        return $this;
    }

    public function removeFeature(EnumDeviceFeature $feature): DeviceInterface
    {
        if ($this->hasFeature($feature)) {
            $key = array_search($feature->value, $this->features);
            if ($key) {
                unset($this->features[$key]);
            }
        }

        return $this;
    }

    public function hasFeature(EnumDeviceFeature $feature): bool
    {
        return in_array($feature->value, $this->features);
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): Device
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getHostname(): ?string
    {
        return $this->hostname;
    }

    public function setHostname(?string $hostname): Device
    {
        $this->hostname = $hostname;

        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(?string $ipAddress): Device
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    public function getMacAddress(): ?string
    {
        return $this->macAddress;
    }

    public function setMacAddress(?string $macAddress): Device
    {
        $this->macAddress = $macAddress;

        return $this;
    }

    public function getNetVendor(): ?string
    {
        return $this->netVendor;
    }

    public function setNetVendor(?string $netVendor): Device
    {
        $this->netVendor = $netVendor;

        return $this;
    }

    public function isDraft(): bool
    {
        return $this->draft;
    }

    public function setDraft(bool $draft): Device
    {
        $this->draft = $draft;

        return $this;
    }

    public function isOnline(): bool
    {
        return $this->online;
    }

    public function setOnline(bool $online): Device
    {
        $this->online = $online;

        return $this;
    }

    public function getUptime(): ?\DateTimeImmutable
    {
        return $this->uptime;
    }

    public function setUptime(\DateTimeImmutable $uptime = null): Device
    {
        $this->uptime = $uptime;

        return $this;
    }
}
