<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;
use Symfony\Component\Uid\Uuid;

interface UserInterface extends SymfonyUserInterface, PasswordAuthenticatedUserInterface
{
    public function getId(): ?Uuid;

    public function setUsername(string $username): self;

    public function getUsername(): ?string;

    public function setEmail(string $email): self;

    public function getEmail(): ?string;

    public function setPassword(string $password): self;

    public function getPassword(): ?string;

    public function setName(string $name): self;

    public function getName(): ?string;

    public function addRole(string $role): self;
}
