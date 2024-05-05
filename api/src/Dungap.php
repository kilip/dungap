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
    public const OnServiceValidated = 'onServiceValidated';
    public const OnStateChanged = 'onStateChanged';

    public const PowerOnFeature = 'PowerOnFeature';
    public const PowerOffFeature = 'PowerOffFeature';
    public const RebootFeature = 'RebootFeature';

    public const PowerOnProcessorTag = 'dungap.node.processor.power_on';
    public const SshDriver = 'ssh';
    public const RouterOsDriver = 'routeros';
    public const EtherWakeDriver = 'EtherWake';
}
