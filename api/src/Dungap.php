<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap;

class Dungap
{
    public const NodeOnlineStateName = 'node.online';
    public const NodeLatencyState = 'node.latency';

    public const OnlineState = 'online';
    public const OfflineState = 'offline';

    public const OnTaskPreRun = 'onTaskPreRun';
    public const OnNodeAdded = 'onNodeAdded';
    public const OnStateUpdated = 'onStateUpdated';
    public const OnServiceScanned = 'onServiceScanned';
}
