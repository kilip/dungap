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
use Dungap\Bridge\Goss\Contracts\GossResultInterface;
use Dungap\Bridge\Goss\Report\Result;
use Dungap\Contracts\Service\ServiceInterface;
use Dungap\Service\Entity\Service;

class Report implements GossReportInterface
{
    /**
     * @var array<string, int>
     */
    private array $idMap = [];

    /**
     * @var array<string,int>
     */
    private array $tcpMap = [];

    /**
     * @var iterable<Result>
     */
    private iterable $results = [];

    /**
     * @param iterable<Result> $results
     */
    public function __construct(
        private readonly Summary $summary,
        iterable                 $results,
    ) {
        foreach($results as $result) {
            $this->addResult($result);
        }
    }

    public function hasResult(GossConfigInterface $config): bool
    {
        return true;
    }

    public function addResult(Result $result): void
    {

        $this->results[] = $result;

        $idRegex = "/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/";
        $tcpRegex = '/tcp.*$/';
        preg_match($idRegex, $result->resourceId, $idMatches);
        preg_match($tcpRegex, $result->resourceId, $tcpMatches);

        $index = count($this->results)-1;
        if(array_key_exists(0, $idMatches)) {
            $this->idMap[$idMatches[0]] = $index;
        }
        if(array_key_exists(0, $tcpMatches)) {
            $this->tcpMap[$tcpMatches[0]] = $index;
        }
    }

    public function getResults(): iterable
    {
        return $this->results;
    }

    public function getSummary(): Summary
    {
        return $this->summary;
    }

    public function findByService(ServiceInterface $service): ?GossResultInterface
    {
        $address = $service->getDevice()->getIpAddress();
        $port = $service->getPort();
        $key = "tcp://{$address}:{$port}";
        $index =  array_key_exists($key, $this->tcpMap) ? $this->tcpMap[$key] : null;

        return is_null($index) ? null : $this->results[$index];
    }
}
