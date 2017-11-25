<?php
/**
 * Created by PhpStorm.
 * User: elycin
 * Date: 11/24/17
 * Time: 2:07 PM
 */

namespace Elycin\ClamAV;


class Daemon
{
    private $socket_path;
    private $socket;
    private $buffer_length = 1024;

    private $character_prefix = "n";

    public function __construct($socket_path = "/var/run/clamav/clamd.ctl")
    {
        $this->socket_path = $socket_path;
        if (!$this->doesSocketExist()) return $this->exceptionSocketDoesNotExist();
    }

    public function doesSocketExist()
    {
        return is_file($this->socket_path);
    }

    private function connect()
    {
        try {
            return fsockopen("unix://" . $this->socket_path);
        } catch (\Exception $exception) {
            return $this->exceptionSocketDoesNotExist();
        }
    }

    private function send($query)
    {
        $this->socket = $this->connect();
        fwrite($this->socket, $query);
        $response = fread($this->socket, 1024);
        return ($response != "UNKNOWN COMMAND\n") ? $response : new \Exception("ClamAV Daemon returned Unknown Command: " . $query);
    }

    public function isFileVirus($file_path)
    {
        return $this->send("nSCAN " . trim($file_path));
    }

    public function __call($name, $arguments)
    {
        $pending_command = trim(sprintf(
                "%s%s %s",
                $this->character_prefix, strtoupper($name), $arguments[0]
            )) . "\n";
        return $this->send($pending_command);
    }

    private function exceptionSocketDoesNotExist(){
        return new \Exception(sprintf("IPC Socket File %s does not exist.", $this->socket_path));
    }
}
