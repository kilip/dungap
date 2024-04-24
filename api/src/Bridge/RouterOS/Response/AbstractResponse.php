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

use Dungap\Bridge\RouterOS\Exception;

abstract class AbstractResponse
{
    /**
     * @param array<string, mixed> $json
     */
    public function __construct(
        protected array $json
    ) {
    }

    /**
     * @return array<string,string>
     */
    abstract public function getPropertyMap(): array;

    public function __get(string $propName): int|string|bool|null
    {
        $map = $this->getPropertyMap();
        $json = $this->json;

        if (!array_key_exists($propName, $map)) {
            throw Exception::propertyNotFound($this, $propName);
        }

        $jsonName = $map[$propName];
        if (!array_key_exists($jsonName, $json)) {
            return null;
        }

        $value = $json[$jsonName];

        if (in_array($value, ['true', 'false'])) {
            return 'true' === $value;
        }

        if (in_array($value, ['yes', 'no'])) {
            return 'yes' === $value;
        }

        return $json[$jsonName];
    }
}
