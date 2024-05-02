<?php

namespace Dungap\Bridge\Goss;

class Util
{
    public static function getServiceId(string $resourceId): ?string
    {
        $regex = '/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/';
        preg_match($regex, $resourceId, $matches);
        return array_key_exists(0, $matches) ? $matches[0]:null;
    }

    public static function getTcpID(string $resourceId): ?string
    {
        $regex = '/tcp:\/\/.*:[1-9]+/';

        preg_match($regex, $resourceId, $matches);

        return array_key_exists(0, $matches) ? $matches[0]:null;
    }
}
