<?php

namespace zvsv\commonLogger;

/**
 * Class Logger
 * (Singleton)
 *
 * @package zvsv\Logger
 */

class Logger
{
    protected static $instance;
    protected $sended = false; //for check the sending. Send only one time.
    //serial input data
    protected $serial_data = [];

    public function __construct(array $params) {
        //set parameters from file with parametrs
        Parameters::set($params);
    }

    /**
     * The method take object's instance which is a object of file then set to a object message (data)
     * If you need to add some directory front the file,
     * you will be able to write the directory before file like this example: 'some_folder/some_file'
     *
     * @param string $name - log file name
     * @param array $data - data for insert into log file
     * @param bool $send - if you want to send the message to admin
     */
    public function write(string $name, array $data, bool $send = false) {
        $Object = Pool::get($name);
        $Object->setDispatch($send)->setMessage($data)->write();
    }

    /**
     * You can use the method if you need to send message for your alert.
     * This method must be to call only one time in the end of the app.
     */
    public function send() {
        if($this->sended) return;

        $message = '';
        foreach (Pool::getAll() AS $Object){
            $message .= "\n*************************\n"
                .$Object->getName()."\n"
                ."*************************"
                .$Object->getMessage(true);
        }

        if($message) {
            //Sending full message to email
            $this->sendEmail(str_replace("\n", '<br>', $message));
            //Sending short message to Telegram
            Telegram::send('You have a new message from ZvsvLogger. You can see the message on '
                .Parameters::get('mail_to'));
        }

        $this->sended = true;
    }

    /**
     * The method for sending email with log message which allowed to send
     *
     * @param string $message
     * @return bool
     */
    public static function sendEmail(string $message) : bool {
        $mailSMTP = new EmailSender(
            Parameters::get('smtp_username'),
            Parameters::get('smtp_password'),
            Parameters::get('smtp_host'),
            Parameters::get('smtp_from'),
            Parameters::get('smtp_port')
        );

        $headers= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "From: Logger <>\r\n";
        $result = $mailSMTP->send(Parameters::get('mail_to'), 'Logger message', $message, $headers);
        if($result === true){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Instance of singleton
     *
     * @param string|null $params_path
     * @return Logger
     */
    public static function getInstance(string $params_path = null)
    {
        if (!(self::$instance instanceof self)) {
            $params = require $params_path;
            self::$instance = new self($params);
        }
        return self::$instance;
    }
}