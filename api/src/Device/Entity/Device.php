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
use Dungap\Contracts\Device\CategoryInterface;
use Dungap\Contracts\Device\DeviceInterface;
use Dungap\Device\Controller\PowerOff;
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
        new Get(
            uriTemplate: '/devices/{id}/power-off',
            controller: PowerOff::class,
            security: 'is_granted("ROLE_ADMIN")',
            write: false,
            name: 'api_device_power_off'
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
    private ?string $name = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $notes = null;

    #[ApiFilter(OrderFilter::class)]
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $hostname = null;

    #[ORM\Column(type: 'string', unique: true, nullable: true)]
    private ?string $ipAddress = null;

    #[ORM\Column(type: 'string', unique: true, nullable: true)]
    private ?string $macAddress = null;

    #[ORM\ManyToOne(targetEntity: CategoryInterface::class, cascade: ['persist'])]
    private CategoryInterface $category;

    #[ORM\Column(type: 'boolean')]
    private bool $draft = false;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $nickname): Device
    {
        $this->name = $nickname;

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

    public function isDraft(): bool
    {
        return $this->draft;
    }

    public function setDraft(bool $draft): Device
    {
        $this->draft = $draft;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): Device
    {
        $this->notes = $notes;

        return $this;
    }

    public function getCategory(): CategoryInterface
    {
        return $this->category;
    }

    public function setCategory(CategoryInterface $category): Device
    {
        $this->category = $category;

        return $this;
    }
}
