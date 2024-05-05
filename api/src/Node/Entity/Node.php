<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Node\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;
use Dungap\Contracts\Node\NodeInterface;
use Dungap\Core\Entity\UuidConcern;
use Dungap\Node\Controller\PowerOffAction;
use Dungap\Node\Controller\PowerOnAction;
use Dungap\Node\Controller\RebootAction;
use Dungap\Node\Listener\NodeEntityListener;

#[ApiResource(
    operations: [
        new Post(security: 'is_granted("ROLE_ADMIN")'),
        new GetCollection(),
        new Get(),
        new Patch(security: 'is_granted("ROLE_ADMIN")'),
        new Delete(security: 'is_granted("ROLE_ADMIN")'),
        new Get(
            uriTemplate: '/nodes/{id}/power-on',
            controller: PowerOnAction::class,
            security: 'is_granted("ROLE_ADMIN")',
            write: false,
            name: 'api_node_power_on'
        ),
        new Get(
            uriTemplate: '/nodes/{id}/power-off',
            controller: PowerOffAction::class,
            security: 'is_granted("ROLE_ADMIN")',
            write: false,
            name: 'api_node_power_off'
        ),
        new Get(
            uriTemplate: '/nodes/{id}/reboot',
            controller: RebootAction::class,
            security: 'is_granted("ROLE_ADMIN")',
            write: false,
            name: 'api_node_reboot'
        ),
    ],
    mercure: true
)]
#[ORM\Entity()]
#[ORM\EntityListeners([
    NodeEntityListener::class,
])]
#[ORM\Table(name: 'dungap_node')]
class Node implements NodeInterface
{
    use UuidConcern;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $hostname = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $ip = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $mac = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $note = null;

    private bool $online = false;

    private ?float $latency = null;

    public function getStates(): NodeStates
    {
        return NodeStates::create($this);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): void
    {
        $this->ip = $ip;
    }

    public function getMac(): ?string
    {
        return $this->mac;
    }

    public function setMac(?string $mac): void
    {
        $this->mac = $mac;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): void
    {
        $this->note = $note;
    }

    public function isOnline(): bool
    {
        return $this->online;
    }

    public function setOnline(bool $online): void
    {
        $this->online = $online;
    }

    public function getHostname(): ?string
    {
        return $this->hostname;
    }

    public function setHostname(?string $hostname): void
    {
        $this->hostname = $hostname;
    }

    public function getLatency(): ?float
    {
        return $this->latency;
    }

    public function setLatency(?float $latency): void
    {
        $this->latency = $latency;
    }
}
