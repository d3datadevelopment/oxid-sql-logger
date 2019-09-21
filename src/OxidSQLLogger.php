<?php

/**
 * @author    Tobias Matthaiou <developer@tobimat.eu>
 * @author    D3 Data Development - Daniel Seifert <support@shopmodule.com>
 */

namespace D3\OxidSqlLogger;

use Doctrine\DBAL\Logging\SQLLogger;
use Monolog;

/**
 * Class OxidSQLLogger
 */
class OxidSQLLogger implements SQLLogger
{
    public $message;
    public $logStartingFile;
    public $logStartingLine;
    public $logStartingClass;
    public $logStartingFunction;

    /**
     * @inheritDoc
     */
    public function __construct($file, $line, $class, $function, $message = null)
    {
        if (!Monolog\Registry::hasLogger('sql')) {
            Monolog\Registry::addLogger((new LoggerFactory())->create('sql'));
        }

        $this->message             = $message;
        $this->logStartingFile      = $file;
        $this->logStartingLine      = $line;
        $this->logStartingClass     = $class;
        $this->logStartingFunction  = $function;
    }

    /**
     * @inheritDoc
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        Monolog\Registry::sql()->addDebug(
            $this->message ? $this->message : $sql,
            [
                'query'                 => $sql,
                'params'                => $params,
                'types'                 => $types,
                'logStartingFile'       => $this->logStartingFile,
                'logStartingLine'       => $this->logStartingLine,
                'logStartingClass'      => $this->logStartingClass,
                'logStartingFunction'   => $this->logStartingFunction,

            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function stopQuery()
    {
    }
}
