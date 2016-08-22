<?php
/**
 * NOTICE OF LICENSE.
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 *  @author    Tappz Team
 *  @copyright 2009-2016 Tmob
 *  @license   LICENSE.txt
 */

class TLogger
{
    private $logfile;
    private $separator;
    private $headers;
    const LOG_PATH = '/../log';
    const DEFAULT_TAG = '--';

    public function __construct($logfilename = '/log.csv', $separator = '|')
    {
        $this->logfile = dirname(__FILE__).self::LOG_PATH.$logfilename;
        $this->separator = $separator;
        $this->headers =
            'DATETIME'.$this->separator.
            'ERRORLEVEL'.$this->separator.
            'TAG'.$this->separator.
            'VALUE'.$this->separator.
            'LINE'.$this->separator.
            'FILE';
    }

    private function log($errorlevel = 'INFO', $value = '')
    {
        $datetime = @date('Y-m-d H:i:s');
        if (!file_exists($this->logfile)) {
            $headers = $this->headers."\n";
        }
        $fd = fopen($this->logfile, 'a');
        if (@$headers) {
            fwrite($fd, $headers);
        }
        $debugBacktrace = debug_backtrace();
        $line = $debugBacktrace[1]['line'];
        $file = $debugBacktrace[1]['file'];
        $value = preg_replace('/\s+/', ' ', trim($value));
        $entry = array($datetime, $errorlevel, self::DEFAULT_TAG, $value, $line, $file);
        fputcsv($fd, $entry, $this->separator);
        fclose($fd);
    }

    public function info($value = '', $tag = self::DEFAULT_TAG)
    {
        self::log('INFO', $value, $tag);
    }

    public function warning($value = '', $tag = self::DEFAULT_TAG)
    {
        self::log('WARNING', $value, $tag);
    }

    public function error($value = '', $tag = self::DEFAULT_TAG)
    {
        self::log('ERROR', $value, $tag);
    }

    public function debug($value = '', $tag = self::DEFAULT_TAG)
    {
        self::log('DEBUG', $value, $tag);
    }
}
