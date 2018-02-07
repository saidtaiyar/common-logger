<?php

namespace zvsv\commonLogger;

class Telegram
{
    public $message = '';
    private static $_instance = null;

    private function __construct() {
        // приватный конструктор ограничивает реализацию getInstance ()
    }
    protected function __clone() {
        // ограничивает клонирование объекта
    }

    public function addMessage($message, $data_msg = []){
        $message = $message.($data_msg ? json_encode($data_msg) : '')."\n\n-------\n";
        $this->message .= $message;
    }

    public function sendMessage(){
        if($this->message) {
            self::send($this->message);
        }
        $this->message = '';
    }

    static public function getInstance() {
        if(is_null(self::$_instance))
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public static function send($text, $data_msg = []){
        $params = [
            'chat_id' => Parameters::get('telegram_chat_id'),
            'text' => $text.($data_msg ? json_encode($data_msg) : '')
        ];
        file_get_contents(
            Parameters::get('telegram_url')
            .'sendMessage?'. http_build_query($params)
        );
        sleep(1);
    }
}