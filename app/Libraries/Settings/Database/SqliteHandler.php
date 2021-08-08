<?php

namespace App\Libraries\Settings\Database;

use App\Libraries\Settings\Env\EnvHandler;

class SqliteHandler
{
    public const SEARCH_KEY = 'APP_NAME';
    public const DEFAULT_NAME_DB = 'sqlitedb';

    /**
     * Create Sqlite DB. Return path to DB.
     *
     * @param string $env_content
     *
     * @return string $file_path.
     * */
    public static function createSqliteDB($env_content)
    {
        $db_name = EnvHandler::returnKeyAndValueFromDotEnvContent($env_content, self::SEARCH_KEY)['value'] ?? self::DEFAULT_NAME_DB;

        $base_path = base_path() . '/storage/database/';

        if (!file_exists($base_path)) {
            mkdir($base_path, 0777, true);
        }

        $file_path = $base_path . strtolower(
            str_ireplace(
                ' ',
                '-',
                trim(
                    $db_name,
                    '"'
                )
            )
        ) . '.sqlite';

        if (!file_exists($file_path)) {
            $fp = fopen($file_path, 'w');
            fclose($fp);
        }

        touch($file_path);

        return $file_path;
    }

    /**
     * Set DB settings in config.
     *
     * @param string $file_path
     *
     * @return void
     * */
    public static function setBaseSettings($file_path)
    {
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.foreign_key_constraints' => true]);
        config(['database.connections.sqlite.database' => $file_path]);
    }
}
