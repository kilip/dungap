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
    public const OnServiceCreated = 'onServiceCreated';
    public const OnStateChanged = 'onStateChanged';

    public const PowerOnFeature = 'PowerOn';
    public const PowerOffFeature = 'PowerOff';
    public const RebootFeature = 'Reboot';

    public const PowerOnProcessorTag = 'dungap.node.processor.power_on';
    public const PowerOffProcessorTag = 'dungap.node.processor.power_off';
    public const RebootProcessorTag = 'dungap.node.processor.reboot';

    public const SshDriver = 'SSH';
    public const RouterOsDriver = 'RouterOS';
    public const EtherWakeDriver = 'EtherWake';
    const PrometheusDriver= 'Prometheus';

    const NodeExporterSSH = self::SshDriver;
    const NodeExporterRouterOS = self::RouterOsDriver;
    const NodeExporterPrometheus = self::PrometheusDriver;
}
