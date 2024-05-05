<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\State\Controller;

use Dungap\Contracts\State\StateInterface;
use Dungap\Contracts\State\StateRepositoryInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
final readonly class LatestAction
{
    public function __construct(
        private StateRepositoryInterface $states,
    ) {
    }

    public function __invoke(string $id): ?StateInterface
    {
        return $this->states->findLatest($id);
    }
}
