<?php

namespace App\Libraries\Settings\Database;

use App\Libraries\Settings\Env\EnvHandler;

class MysqlHandler
{
    /**
     * Set DB settings in config.
     *
     * @param string $type
     * @param string $env_content
     *
     * @return void
     * */
    public static function setBaseSettings($type, $env_content)
    {
        config(['database.default' => $type]);
        config(['database.connections.' . $type . '.driver' => EnvHandler::returnKeyAndValueFromDotEnvContent($env_content, 'DB_CONNECTION')['value'] ?? 'mysql']);
        config(['database.connections.' . $type . '.host' => EnvHandler::returnKeyAndValueFromDotEnvContent($env_content, 'DB_HOST')['value'] ?? '127.0.0.1']);
        config(['database.connections.' . $type . '.port' => EnvHandler::returnKeyAndValueFromDotEnvContent($env_content, 'DB_PORT')['value'] ?? '3306']);
        config(['database.connections.' . $type . '.database' => EnvHandler::returnKeyAndValueFromDotEnvContent($env_content, 'DB_DATABASE')['value'] ?? '']);
        config(['database.connections.' . $type . '.username' => EnvHandler::returnKeyAndValueFromDotEnvContent($env_content, 'DB_USERNAME')['value'] ?? '']);
        config(['database.connections.' . $type . '.password' => EnvHandler::returnKeyAndValueFromDotEnvContent($env_content, 'DB_PASSWORD')['value'] ?? '']);
    }
}
