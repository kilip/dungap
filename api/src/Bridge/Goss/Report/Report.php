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
use Dungap\Contracts\Service\ServiceInterface;
use Dungap\Contracts\Service\ServiceRepositoryInterface;
use Dungap\Contracts\Service\ValidatorResultInterface;

class Report implements GossReportInterface
{
    /**
     * @var array<string, int>
     */
    private array $serviceIds = [];

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
        foreach ($results as $result) {
            $this->addResult($result);
        }
    }

    public function addResult(Result $result): void
    {
        $this->results[] = $result;
        $index = count($this->results) - 1;
        $this->serviceIds[$result->getServiceId()] = $index;
        $this->tcpMap[$result->getTcpId()] = $index;
    }

    /**
     * @return iterable<ValidatorResultInterface>
     */
    public function getResults(): iterable
    {
        return $this->results;
    }

    public function getSummary(): Summary
    {
        return $this->summary;
    }

    public function findByService(ServiceInterface $service): ?ValidatorResultInterface
    {
        $serviceId = $service->getId();

        if(array_key_exists($serviceId, $this->serviceIds)){
            $index = $this->serviceIds[$serviceId];
        }else{
            $address = $service->getDevice()->getIpAddress();
            $port = $service->getPort();
            $key = "tcp://{$address}:{$port}";
            $index = array_key_exists($key, $this->tcpMap) ? $this->tcpMap[$key] : null;
        }
        return is_null($index) ? null : $this->results[$index];
    }
}
