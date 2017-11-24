<?php
/**
 * Created by PhpStorm.
 * User: elycin
 * Date: 11/24/17
 * Time: 2:08 PM
 */

require_once __DIR__ . "/../src/Elycin/ClamAV/Daemon.php";

class eicar_virus
{
    private $clamav;

    public function __construct()
    {
        $this->clamav = new \Elycin\ClamAV\Daemon();
        $this->information();
        $this->downloadEicar();
        echo $this->clamav->scan(sprintf("%s/eicar.txt", __DIR__));
        unlink("eicar.txt");
    }

    public function information()
    {
        foreach ([
                     "Test Script Documentation:",
                     "This script downloads and tests ClamAV against the EICAR Test Signature",
                     "The file will be downloaded to " . sprintf("%s/eicar.txt", __DIR__),
            "If ClamAV's Daemon is working you will see the following response: Eicar-Test-Signature FOUND"
                 ] as $line) echo sprintf("%s\n", $line);
        echo str_repeat("-", 40) . "\n";
    }

    public function downloadEicar()
    {
        $file_stream = fopen("eicar.txt", "w");
        fwrite($file_stream, file_get_contents("http://www.eicar.org/download/eicar.com.txt"));
        fclose($file_stream);
    }
}

new eicar_virus();
