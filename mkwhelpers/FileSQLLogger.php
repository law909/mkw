<?php

namespace mkwhelpers;

use \Doctrine\DBAL\Logging;

class FileSQLLogger implements \Doctrine\DBAL\Logging\SQLLogger {

    private $logfilename = 'sql.log';

    public function __construct($logfname) {
        $this->logfilename = $logfname;
    }

    public function logSQL($sql, array $params = null) {
        $logfile = fopen($this->logfilename, 'a');
        fwrite($logfile, microtime() . ':' . $sql . "\r\n");
        fclose($logfile);
    }

    public function startQuery($sql, array $params = null, array $types = null) {
        $logfile = fopen($this->logfilename, 'a');
        fwrite($logfile, microtime() . ':' . $sql . "\r\n");
        if (is_array($params)) {
            foreach ($params as $param) {
                if ($param instanceof \DateTime) {
                    fwrite($logfile, '  ' . $param->format('Y-m-d'));
                }
                else {
                    fwrite($logfile, '  ' . $param);
                }
            }
        }
        fwrite($logfile, "\r\n");
        fclose($logfile);
    }

    public function stopQuery() {
        $logfile = fopen($this->logfilename, 'a');
        fwrite($logfile, microtime() . ':----' . "\r\n");
        fclose($logfile);
    }
}