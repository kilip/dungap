<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\User;

interface UserRepositoryInterface
{
    public function findByUsernameOrEmail(string $usernameOrEmail): ?UserInterface;

    public function findByEmail(string $email): ?UserInterface;

    public function remove(UserInterface $user): void;

    public function create(): UserInterface;

    public function save(UserInterface $user): void;
}
