<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\SSH\Entity;

use Doctrine\ORM\Mapping as ORM;
use Dungap\Bridge\SSH\Contracts\NodeConfigInterface;
use Dungap\Contracts\Node\NodeInterface;
use Dungap\Core\Entity\UuidConcern;

#[ORM\Entity]
#[ORM\Table('ssh_node_config')]
class NodeConfig implements NodeConfigInterface
{
    use UuidConcern;

    #[ORM\OneToOne(targetEntity: NodeInterface::class, orphanRemoval: true)]
    private NodeInterface $node;

    #[ORM\Column(type: 'string')]
    private string $username;

    #[ORM\Column(type: 'smallint')]
    private int $port = 22;

    #[ORM\Column(type: 'smallint')]
    private int $timeout = 5;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $password = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $privateKey = null;

    public function getNode(): NodeInterface
    {
        return $this->node;
    }

    public function setNode(NodeInterface $node): void
    {
        $this->node = $node;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function setPort(int $port): void
    {
        $this->port = $port;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function setTimeout(int $timeout): void
    {
        $this->timeout = $timeout;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getPrivateKey(): ?string
    {
        return $this->privateKey;
    }

    public function setPrivateKey(?string $privateKey): void
    {
        $this->privateKey = $privateKey;
    }
}
