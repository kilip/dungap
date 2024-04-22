<?php

namespace Dungap\User\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\ORM\Mapping as ORM;
use Faker\Core\Uuid;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new GetCollection()
    ],
    mercure: true
)]
#[ORM\Entity]
#[UniqueEntity('email')]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    private ?Uuid $id = null;

    /**
     * @see https://schema.org/email
     */
    #[ORM\Column(unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private ?string $name = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function eraseCredentials(): void
    {
    }

    /**
     * @return array<int, string>
     */
    public function getRoles(): array
    {
        $this->roles[] = 'ROLE_USER';
        return array_unique($this->roles, SORT_ASC);
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles);
    }

    public function addRole(string $role): void
    {
        if(!$this->hasRole($role)){
            $this->roles[] = $role;
        }
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): User
    {
        $this->name = $name;
        return $this;
    }
}