<?php

namespace zvsv\commonLogger;

/**
 * The class makes a object which does the next:
 * - open a file or create a file and writes to this file some message
 * - merge the messages and keep them
 *
 * Class LogFileObject
 * @package zvsv\commonLogger
 */
class LogFileObject implements Object
{
    protected $name; //name file
    protected $dispatch; //if you need to send the message
    protected $data = []; //data for log file (array)
    protected $message = ''; //message for log file (string)
    protected $message_for_sending = ''; //message for sending (string)
    protected $fp; //opened file

    public function __construct(string $name) {
        $this->name = $name;
    }

    /**
     * Write to file
     */
    public function write() {
        $size = Parameters::get('max_file_size'); //max size file
        $root = Parameters::get('files_root');
        $file = $root.$this->name;
        $file_path_arr = explode('/', $file);
        $only_file = $file_path_arr[count($file_path_arr)-1];
        $file_path = str_replace($only_file, '', $file);
        $only_file = explode('.', $only_file);

        //Check and if it need create new directories with recursion
        Helper::createDir($file_path);

        //Get the last file
        $file = glob("{$file_path}{$only_file[0]}-[0-9]*.log");
        $file = isset($file[count($file)-1]) ? $file[count($file)-1] : FALSE ;

        if(!$file){
            //We need to crete a new file if this didn't find
            $file = self::newFilePath($file_path, $only_file);
        } else if($size && filesize($file) > ($size * 1024 * 1024)) {
            //We need to create a new file if its size more than allowed
            $file = self::newFilePath($file_path, $only_file);
        }

        //Open or create the file
        $this->fileOpen($file);

        fwrite($this->fp, $this->message);
        fclose($this->fp);
    }

    /**
     * Open the file from disc
     *
     * @param string $file
     */
    protected function fileOpen(string $file) {
        if(!file_exists($file)){
            $this->fp = fopen($file, "w");
            chmod($file, 0755);
        } else {
            $this->fp = fopen($file, "a");
        }
    }

    /**
     * The method creates new path of file
     *
     * @param $file_path - path to file
     * @param $only_file - it's only file name
     * @return string - string contain both path to file and file name
     */
    protected static function newFilePath($file_path, $only_file){
        $only_file = $only_file[0].'-'.date("YmdHis", time()).(isset($only_file[1]) ? '.'.$only_file[1] : '');
        return $file_path.$only_file.'.log';
    }

    /**
     * It's sets message from data array and convert to string for file.
     * Message starts from date string. If it's addition then message appends without date.
     *
     * @param array $data
     * @return $this
     */
    public function setMessage(array $data) {
        $content = print_r($data, true);
        $datetime = gmdate("Y-m-d  H:i:s", time());
        $this->message .= !$this->message
            ? "\n\n===[".$datetime."]\n   ---".$content
            : "\n   ---".$content;

        //if parameter dispatch has value bool(true) then the code create message for sending
        if($this->dispatch) {
            $this->message_for_sending .= !$this->message_for_sending
                ? "\n===[".$datetime."]\n   ---".$content
                : "\n   ---".$content;
        }

        return $this;
    }

    /**
     * It's means that the message of object will send to admin.
     *
     * @param $value
     * @return $this
     */
    public function setDispatch(bool $value) {
        $this->dispatch = $value;

        return $this;
    }

    /**
     * The method returns value "dispatch"
     *
     * @return bool $this->dispatch
     */
    public function isDispatch() : bool {
        return $this->dispatch;
    }

    /**
     * The method returns value "message"
     *
     * @param bool $for_sending - if you need to send a message, you must set the parameter into true
     * @return string $this->message
     */
    public function getMessage($for_sending=false) : string {
        return $for_sending ? $this->message_for_sending : $this->message;
    }

    /**
     * The method returns value "name"
     *
     * @return string $this->name
     */
    public function getName() : string {
        return $this->name;
    }
}