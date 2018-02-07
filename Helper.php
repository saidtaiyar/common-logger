<?php

namespace zvsv\commonLogger;

/**
 * The class contains some methods for help of other code
 *
 * Class Helper
 * @package zvsv\Logger
 */
class Helper
{
    /**
     * Create new path (recursion)
     *
     * @param string $path
     * @param int $mode - permissions
     */
    public static function createDir(string $path, $mode = 0775)
    {
        if (is_dir($path)) {
            return true;
        }
        $parentDir = dirname($path);
        if (!is_dir($parentDir) && $parentDir !== $path) {
            static::createDir($parentDir, $mode, true);
        }
        try {
            if (!mkdir($path, $mode)) {
                return false;
            }
        } catch (\Exception $e) {
            if (!is_dir($path)) {
                throw new \yii\base\Exception("Failed to create directory \"$path\": " . $e->getMessage(), $e->getCode(), $e);
            }
        }
        try {
            return chmod($path, $mode);
        } catch (\Exception $e) {
            throw new \yii\base\Exception("Failed to change permissions for directory \"$path\": " . $e->getMessage(), $e->getCode(), $e);
        }
    }
}