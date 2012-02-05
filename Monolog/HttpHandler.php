<?php

namespace Jbohn\Bundle\LogglyBundle\Monolog;

use Monolog\Formatter\JsonFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\AbstractProcessingHandler;

class HttpHandler extends AbstractProcessingHandler
{
    private static $host = 'logs.loggly.com';
    private $input;
    private $format;
    private $secure;

    public function __construct($input, $format = 'json', $secure = true)
    {
        $this->input = $input;
        $this->format = $format;
        $this->secure = $secure;
    }

    public function write(array $record)
    {
        $log = $record['formatted'];
        $protocol = $this->secure ? 'ssl://' : 'tcp://';
        $port = $this->secure ? '443' : '80';
        $mime = $this->format == 'json' ? 'application/json' : 'text/plain';

        $res = fsockopen($protocol . self::$host, $port);

        $message = sprintf("POST /inputs/%s HTTP/1.1\r\n", $this->input);
        $message .= sprintf("Host: %s\r\n", self::$host);
        $message .= sprintf("Content-Type: %s\r\n", $mime);
        $message .= sprintf("Content-Length: %s\r\n", strlen($log));
        $message .= "Connection: Close\r\n\r\n";
        $message .= $log;

        fwrite($res, $message);
        fclose($res);
    }

    protected function getDefaultFormatter()
    {
        return $this->format == 'json' ? new JsonFormatter() : new LineFormatter();
    }
}