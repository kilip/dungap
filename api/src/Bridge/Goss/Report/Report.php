<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\Goss\Report;

use Dungap\Bridge\Goss\Contracts\GossConfigInterface;
use Dungap\Bridge\Goss\Contracts\GossReportInterface;

class Report implements GossReportInterface
{
    /**
     * @param iterable<Result> $results
     */
    public function __construct(
        private Summary $summary,
        private iterable $results
    ) {
    }

    public function hasResult(GossConfigInterface $config): bool
    {
        return true;
    }

    public function addResult(Result $result): void
    {
        $this->results[] = $result;
    }

    public function getResults(): iterable
    {
        return $this->results;
    }

    public function getSummary(): Summary
    {
        return $this->summary;
    }

    public function setSummary(Summary $summary): void
    {
        $this->summary = $summary;
    }
}
