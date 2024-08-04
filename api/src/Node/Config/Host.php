<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Node\Config;

use Dungap\Dungap;

class Host
{
    public function __construct(
        public string $name,
        public ?string $ip = null,
        public ?string $mac = null,
        public ?string $note = null,
        public ?string $exporter = Dungap::NodeExporterSSH
    ) {
    }

    /**
     * @param array<string,string> $config
     */
    public static function fromConfig(array $config): self
    {
        return new self(
            $config['name'],
            $config['ip'],
            $config['mac'],
            $config['note']
        );
    }
}
