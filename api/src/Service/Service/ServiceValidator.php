<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Service\Service;

use Dungap\Contracts\Node\NodeInterface;
use Dungap\Contracts\Service\ServiceReportInterface;
use Dungap\Contracts\Service\ServiceValidatorInterface;
use Dungap\Service\Data\ServiceReport;

class ServiceValidator implements ServiceValidatorInterface
{
    public function validate(NodeInterface $node, int $port, int $timeout): ServiceReportInterface
    {
        // TODO: Implement validate() method.
        return $this->doValidate($node, $port, $timeout);
    }

    private function doValidate(NodeInterface $node, int $port, int $timeout): ServiceReport
    {
        $address = $node->getIp() ?? $node->getHostname();
        $start = microtime(true);

        // fsockopen prints a bunch of errors if a host is unreachable. Hide those
        // irrelevant errors and deal with the results instead.
        $fp = @fsockopen($address, $port, $errno, $errmsg, $timeout / 1000);
        if (!$fp) {
            $latency = null;
        } else {
            $latency = microtime(true) - $start;
            $latency = round($latency * 1000, 4);
            fclose($fp);
        }

        return new ServiceReport(
            node: $node,
            port: $port,
            successful: false !== $fp,
            errorCode: $errno,
            error: $errmsg,
            latency: $latency
        );
    }
}
