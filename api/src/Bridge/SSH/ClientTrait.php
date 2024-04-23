<?php

/*
 * This file is part of the dungap project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dungap\Bridge\SSH;

use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SSH2;

trait ClientTrait
{
    protected function login(SSH2 $client, Setting $setting): void
    {
        $username = $setting->username;
        $password = $setting->password;

        if (!is_null($setting->privateKey)) {
            $privateKey = $setting->privateKey;
            if (is_file($privateKey)) {
                $privateKey = file_get_contents($privateKey);
            }
            $password = PublicKeyLoader::load($privateKey);
        }

        if (!$client->login($username, $password)) {
            throw SecureException::loginFailed($this->host, $setting->port, $setting->username);
        }
    }
}
