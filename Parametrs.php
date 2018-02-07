<?php

namespace zvsv\commonLogger;

/**
 * This is storage where parameters plug in.
 *
 * Class Parameters
 * @package zvsv\Logger
 */
class Parameters
{
    protected static $params = null;

    /**
     * Set the default
     */
    private static function setDefault() {
        self::$params = [
            'max_file_size' => 10,
            'files_root' => '/var/log/php-app-zvsv-logger/',
            //to email
            'mail_to' => '',
            //smtp
            'smtp_username' => 'some email',
            'smtp_port' => '465',
            'smtp_host' =>  'ssl://smtp.yandex.ru', //for example yandex's smtp
            'smtp_password' => 'some pass',
            'smtp_charset' => 'utf-8',
            'smtp_from' => 'some name',
            //telegram
            'telegram_url' => '',
            'telegram_chat_id' => 0,
        ];
    }

    /**
     * Set the external params
     *
     * @param array $data
     */
    public static function set(array $data = []) {
        if(!self::$params){
            self::setDefault();
        }
        self::$params = array_merge(self::$params, $data);
    }

    /**
     * Get param by name
     *
     * @param string $name
     * @return mixed
     */
    public static function get(string $name) {
        return self::$params[$name];
    }

    /**
     * You can get full array of parameters
     *
     * @return null
     */
    public static function getAll() {
        return self::$params;
    }
}