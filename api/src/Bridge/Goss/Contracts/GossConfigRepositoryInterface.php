<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\Goss\Contracts;

use Dungap\Contracts\Service\ServiceInterface;

interface GossConfigRepositoryInterface
{
    public function findByService(ServiceInterface $service): ?GossConfigInterface;

    public function create(): GossConfigInterface;

    public function register(GossConfigInterface $config): void;
}
