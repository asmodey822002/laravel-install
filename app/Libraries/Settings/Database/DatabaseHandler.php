<?php

namespace App\Libraries\Settings\Database;

use App\Libraries\Settings\Env\EnvHandler;

class DatabaseHandler
{
    /**
     * Create Sqlite DB. Return path to DB.
     *
     * @param string $env_content
     *
     * @return string $file_path.
     * */
    public static function createSqliteDB($env_content)
    {
        return SqliteHandler::createSqliteDB($env_content);
    }

    /**
     * Set DB settings in config.
     *
     * @param string $db_type
     * @param string|nullable $db_path
     * @param string|nullable $env_content
     *
     * @return void
     * */
    public static function setBaseSettings($db_type, $db_path = null, $env_content = null)
    {
        if ($db_type === 'sqlite' && $db_path) {
            SqliteHandler::setBaseSettings($db_path);
        }

        if ($db_type === 'mysql' && $env_content) {
            MysqlHandler::setBaseSettings($db_type, $env_content);
        }
    }
}
