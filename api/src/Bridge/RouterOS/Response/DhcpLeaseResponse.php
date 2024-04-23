<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\RouterOS\Response;

/**
 * @property string  $id
 * @property ?string $activeAddress
 * @property ?string $activeClientId
 * @property ?string $activeMacAddress
 * @property ?string $activeServer
 * @property ?string $address
 * @property ?string $addressLists
 * @property bool    $blocked
 * @property ?string $comment
 * @property ?string $dhcpOption
 * @property bool    $disabled
 * @property bool    $dynamic
 * @property ?string $expiresAfter
 * @property ?string $hostName
 * @property ?string $lastSeen
 * @property ?string $macAddress
 * @property bool    $radius
 * @property ?string $server
 * @property ?string $status
 */
final class DhcpLeaseResponse extends AbstractResponse
{
    public function getPropertyMap(): array
    {
        return [
            'id' => '.id',
            'activeAddress' => 'active-address',
            'activeClientId' => 'active-client-id',
            'activeMacAddress' => 'active-mac-address',
            'activeServer' => 'active-server',
            'address' => 'address',
            'addressLists' => 'address-lists',
            'blocked' => 'blocked',
            'comment' => 'comment',
            'dhcpOption' => 'dhcp-option',
            'disabled' => 'disabled',
            'dynamic' => 'dynamic',
            'expiresAfter' => 'expires-after',
            'hostName' => 'host-name',
            'lastSeen' => 'last-seen',
            'macAddress' => 'mac-address',
            'radius' => 'radius',
            'server' => 'server',
            'status' => 'status',
        ];
    }
}
