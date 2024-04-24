<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Setting\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Dungap\Contracts\Setting\SettingInterface;
use Dungap\Setting\Repository\SettingRepository;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ApiResource(mercure: true)]
#[ORM\Entity(repositoryClass: SettingRepository::class)]
class Setting implements SettingInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME)]
    private ?Uuid $id = null;

    #[ORM\Column(type: 'string', unique: true)]
    public string $key;

    #[ORM\Column(type: 'json_document')]
    public object $value;

    #[ORM\Column(type: UuidType::NAME, nullable: true)]
    public ?Uuid $relId = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): Setting
    {
        $this->key = $key;

        return $this;
    }

    public function getValue(): object
    {
        return $this->value;
    }

    public function setValue(object $value): Setting
    {
        $this->value = $value;

        return $this;
    }

    public function getRelId(): ?Uuid
    {
        return $this->relId;
    }

    public function setRelId(?Uuid $relId): Setting
    {
        $this->relId = $relId;

        return $this;
    }
}
