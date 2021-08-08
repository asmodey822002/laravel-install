<?php

namespace App\Libraries\Files;

class FilesHandler
{
    /**
     * Returns the contents of a file or NULL
     *
     * @param string $path
     *
     * @return string|null
     * */
    public static function getFileContents($path)
    {
        return file_exists($path) ? file_get_contents($path) : null;
    }

    /**
     * Writes data to a file
     *
     * @param string $path
     * @param string $contents
     *
     * @return bool
     * */
    public static function writeFile($path, $contents)
    {
        return (bool) file_put_contents($path, $contents, LOCK_EX);
    }

    /**
     * Finds a key/value pair by key
     *
     * @param string $content
     * @param string $key
     * @param string $delimiter
     *
     * @return string|null
     * */
    public static function readAValuePairWithAKey(string $content, string $key, string $delimiter): ?string
    {
        // Match the given key at the beginning of a line
        if (preg_match("#^ *'{$key}' *{$delimiter} *[^\r\n]*$#uimU", $content, $matches)) {
            return $matches[0];
        }

        return null;
    }

    /**
     * Return value from file by key
     *
     * @param string $path
     * @param string $name_file
     * @param string $key
     * @param string $delimiter
     *
     * @return string|null
     * */
    public static function returnValueFromFileByKey(string $path, string $name_file, string $key, string $delimiter): ?string
    {
        if (! file_exists($path . $name_file)) {
            return null;
        }

        $content = static::getFileContents($path . $name_file);

        $str = trim(static::readAValuePairWithAKey($content, $key, $delimiter));
        $arr = explode($delimiter, $str);

        return trim(trim($arr[1], "',"), " '");
    }

    /**
     * Check subfolders and create missing one
     *
     * @param string $base_folder_path
     * @param array $nested_folders_array
     *
     * @return void
     * */
    public static function checkNestedFolders($base_folder_path, $nested_folders_array)
    {
        if (empty($base_folder_path) || ! is_string($base_folder_path) || ! is_array($nested_folders_array)) {
            return;
        }

        $folder_name_to_check = $base_folder_path;
        foreach ($nested_folders_array as $folder_name) {
            $folder_name_to_check .= DIRECTORY_SEPARATOR.$folder_name;
            if (! file_exists($folder_name_to_check)) {
                mkdir($folder_name_to_check);
            } elseif (! is_dir($folder_name_to_check)) {
                return;
            }
        }

        return;
    }
}
