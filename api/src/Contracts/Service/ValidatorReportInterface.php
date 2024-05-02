<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Contracts\Service;

use Dungap\Bridge\Goss\Contracts\GossConfigInterface;

interface ValidatorReportInterface
{
    /**
     * @return iterable<ValidatorResultInterface>
     */
    public function getResults(): iterable;

    public function findByService(ServiceInterface $service): ?ValidatorResultInterface;
}
