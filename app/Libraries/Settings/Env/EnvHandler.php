<?php

namespace App\Libraries\Settings\Env;

use App\Libraries\Files\FilesHandler;

class EnvHandler
{
    public const ENV_EXAMPLE_FILENAME = '.env.example';
    public const ENV_FILENAME = '.env';
    public const KEY_TYPE_OF_MAILER = 'TYPE_OF_MAILER';
    public const DEFAULT_APP_URL_SCHEME = 'http';

    /**
     * Get .env or .env.example files content.
     *
     * @param string|null $env_example
     *
     * @return string
     */
    public static function getEnvFileContent($env_example = false)
    {
        $filename = $env_example ? self::ENV_EXAMPLE_FILENAME : self::ENV_FILENAME;
        $env_example_path = base_path() . '/'. $filename;
        return FilesHandler::getFileContents($env_example_path) ?? '';
    }

    /**
     * Write all data in .env file.
     *
     * @param string $content
     *
     * @return bool.
     */
    public static function writeEnvFileContent($content)
    {
        $env_path = base_path() . '/'. self::ENV_FILENAME;
        return FilesHandler::writeFile($env_path, $content);
    }

    /**
     * Split string into key and value.
     *
     * @param string $key_value_string.
     *
     * @return array ['key' => string key, 'value' => string value].
     * */
    public static function separateKeyValue($key_value_string)
    {
        $result = [
            'key' => '',
            'value' => ''
        ];

        do {
            if (empty($key_value_string) || (strpos($key_value_string, '=') === false)) {
                break;
            }

            $exploded = explode('=', $key_value_string);

            if (empty($exploded)) {
                break;
            }

            $result['key'] = $exploded[0] ?? '';
            $result['value'] = $exploded[1] ?? '';
        } while (false);

        return $result;
    }

    /**
     * Set or update env-variable.
     *
     * @param string $envFileContent Content of the .env file.
     * @param string $key            Name of the variable.
     * @param string $value          Value of the variable.
     *
     * @return array [string newEnvFileContent, bool isNewVariableSet].
     */
    public static function setEnvVariable(string $envFileContent, string $key, string $value): array
    {
        if (empty($key)) {
            return ['content' => $envFileContent, 'new_key' => false, 'empty' =>true];
        }

        $oldPair = static::readKeyValuePair($envFileContent, $key);

        // Wrap values that have a space or equals in quotes to escape them
        if (preg_match('/\s/', $value) || strpos($value, '=') !== false) {
            $value = '"' . $value . '"';
        }

        $newPair = $key . '=' . $value;

        // For existed key.
        if ($oldPair !== null) {
            $replaced = preg_replace('/^' . preg_quote($oldPair, '/') . '$/uimU', $newPair, $envFileContent);
            return ['content' => $replaced, 'new_key' => false];
        }

        // For a new key.
        return ['content' => $envFileContent . "\n" . $newPair . "\n", 'new_key' =>  true];
    }

    /**
     * Read the "key=value" string of a given key from an environment file.
     * This function returns original "key=value" string and doesn't modify it.
     *
     * @param string $envFileContent
     * @param string $key
     *
     * @return string|null Key=value string or null if the key is not exists.
     */
    public static function readKeyValuePair(string $envFileContent, string $key): ?string
    {
        // Match the given key at the beginning of a line
        if (preg_match("#^ *{$key} *= *[^\r\n]*$#uimU", $envFileContent, $matches)) {
            return $matches[0];
        }

        return null;
    }

    /**
     * Returns the value found by $key in $env_content and the $key itself
     *
     * @param string $env_content
     * @param string $key
     *
     * @return array ['key' => string key, 'value' => string value].
     * */
    public static function returnKeyAndValueFromDotEnvContent($env_content, $key)
    {
        return static::separateKeyValue(
            static::readKeyValuePair(
                $env_content, $key
            )
        );
    }

    /**
     * Checks if the passed value matches the key value in $env_content
     *
     * @param string $env_content
     * @param string $key
     * @param string $value
     *
     * @return bool.
     * */
    public static function checkKeyAndValueInEnv($env_content, string $key, string $value)
    {
        $env_value = static::returnKeyAndValueFromDotEnvContent(
            $env_content,
            $key
        )['value'] ?? null;

        if (! $env_value || $env_value !== $value) {
            return true;
        }
        return false;
    }

    /**
     * Returns a new file env in the form of a string in which all values of the $replaceable_keys['deleted']
     * by the values of the $value_replace
     * the values of the $replaceable_keys['add'] array must match the keys of the $value_replace array
     * (if the length of the replacement array is less than the length of the search array, the extra values
     * will be replaced with an empty string)
     *
     * @param array $replaceable_keys
     * @param array $value_replace
     *
     * @return string.
    */
    public static function replaseValueKeyInEnv(array $replaceable_keys, array $value_replace)
    {
        $old_env = static::getEnvFileContent();

        foreach ($replaceable_keys as $arr_key => $arr) {
            if ($arr_key == 'deleted') {
                foreach ($arr as $val_key) {
                    $search[] = EnvHandler::readKeyValuePair($old_env, $val_key);
                }
            } elseif ($arr_key == 'add') {
                foreach ($arr as $val_key) {
                    $replace[] = $val_key . '=' . $value_replace[$val_key];
                }
            }
        }

        return str_replace($search, $replace, $old_env);
    }

    /**
     * Checking the type of the selected mailer
     *
     * @param array $variable_info
     * @param string $env_content
     *
     * @return bool.
     * */
    public static function checkMailer($variable_info, $env_content)
    {
        $type = static::returnKeyAndValueFromDotEnvContent(
            $env_content,
            self::KEY_TYPE_OF_MAILER
        )['value'] ?? null;

        if (! $type) {
            return false;
        }

        if ($type !== $variable_info['type'] && $variable_info['type'] !== 'core') {
            return true;
        }

        return false;
    }

    /**
     * Clears empty values in .env.
     *
     * @param string $env_content.
     *
     * @return string.
     * */
    public static function cleanFromEmptyString(string $env_content)
    {
        $test_arrey = explode("\n", $env_content);
        $resault = [];

        foreach ($test_arrey as $tests_arr) {
            $test_str_in_arr = explode('=', $tests_arr);
            if (!empty($test_str_in_arr[1]) && $test_str_in_arr[1] != 'null') {
                $resault[] = $tests_arr;
            }
        }

        return implode("\n", $resault);
    }

    /**
     * Get parsed app URL
     *
     * @return array|null
     * */
    public static function getParsedAppUrl()
    {
        $app_url_from_env = env('APP_URL', null);

        if (empty($app_url_from_env)) {
            return null;
        }

        $parsed_url = parse_url($app_url_from_env);

        return $parsed_url;
    }

    /**
     * Return scheme parsed app URL
     *
     * @return string
     * */
    public static function getParsedAppUrlScheme()
    {
        $parsed_app_url_info = static::getParsedAppUrl();

        $scheme = $parsed_app_url_info['scheme'] ?? static::DEFAULT_APP_URL_SCHEME;

        return $scheme;
    }
}
