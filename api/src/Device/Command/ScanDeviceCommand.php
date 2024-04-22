<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Device\Command;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class ScanDeviceCommand
{
    /**
     * @param array<int,string> $target
     */
    public function __construct(
        #[Assert\Type(['type' => 'array'])]
        public array $target
    ) {
    }
}
