<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\Node;

use Dungap\Contracts\Core\RepositoryInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @extends RepositoryInterface<FeatureInterface>
 */
interface FeatureRepositoryInterface extends RepositoryInterface
{
    public function findByFeature(Uuid $deviceId, string $feature): ?FeatureInterface;
}
