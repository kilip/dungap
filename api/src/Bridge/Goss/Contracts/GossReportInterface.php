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

use Dungap\Bridge\Goss\Report\Summary;

interface GossReportInterface
{
    public function getSummary(): Summary;

    /**
     * @return iterable<GossResultInterface>
     */
    public function getResults(): iterable;

    public function hasResult(GossConfigInterface $config): bool;
}
