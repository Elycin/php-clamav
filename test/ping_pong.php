<?php
/**
 * Created by PhpStorm.
 * User: elycin
 * Date: 11/24/17
 * Time: 2:08 PM
 */

require_once __DIR__ . "/../src/Elycin/ClamAV/Daemon.php";

class ping_pong
{
    private $clamav;

    public function __construct()
    {
        $this->clamav = new \Elycin\ClamAV\Daemon();
        $this->information();
        echo $this->clamav->ping();
    }

    public function information()
    {
        foreach ([
                     "Test Script Documentation:",
                     "This script tests the clamav-daemon for a ping response",
                     "If the daemon returns 'PONG' then you have a successful connection."
                 ] as $line) echo sprintf("%s\n", $line);
        echo str_repeat("-", 40) . "\n";
    }
}

new ping_pong();
