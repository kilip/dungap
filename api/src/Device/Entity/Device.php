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

use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Contracts\Device\EnumDeviceFeature;
use Dungap\Device\Repository\DeviceRepository;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ApiResource(mercure: true)]
#[ORM\Entity(repositoryClass: DeviceRepository::class)]
class Device implements DeviceInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private ?Uuid $id = null;

    #[ORM\Column(type: 'string', unique: true, nullable: true)]
    private ?string $nickname = null;

    #[ORM\Column(type: 'string', unique: true, nullable: true)]
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
     * @extends Collection<EnumDeviceFeature>
     */
    #[ORM\Column(type: 'json')]
    private array $features = [];

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function addFeature(EnumDeviceFeature $feature): DeviceInterface
    {
        if (!in_array($feature, $this->features)) {
            $this->features[] = $feature;
        }

        return $this;
    }

    public function removeFeature(EnumDeviceFeature $feature): DeviceInterface
    {
        if (($key = array_search($feature, $this->features)) !== false) {
            unset($this->features[$key]);
        }

        return $this;
    }

    public function hasFeature(EnumDeviceFeature $feature): bool
    {
        return in_array($feature, $this->features);
    }

    public function getFeatures(): array
    {
        return $this->features;
    }

    public function setFeatures(array $features): Device
    {
        $this->features = $features;

        return $this;
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
