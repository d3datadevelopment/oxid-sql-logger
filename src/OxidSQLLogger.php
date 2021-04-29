<?php

/**
 * @author    Tobias Matthaiou <developer@tobimat.eu>
 * @author    D3 Data Development - Daniel Seifert <support@shopmodule.com>
 */

namespace D3\OxidSqlLogger;

use D3\ModCfg\Application\Model\d3database;
use Doctrine\DBAL\Logging\SQLLogger;
use Monolog;
use NilPortugues\Sql\QueryFormatter\Formatter;

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
     * @var SQLQuery
     */
    private $SQLQuery = null;

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
        if ($this->SQLQuery) {
            $this->SQLQuery->setCanceled();
            $this->stopQuery();
        }

        $this->getPreparedStatementQuery($sql, $params);

        $this->SQLQuery = (new SQLQuery()) ->setSql($sql)
                                            ->setParams($params)
                                            ->setTypes($types)
                                            ->setLogStartingFile($this->logStartingFile)
                                            ->setLogStartingLine($this->logStartingLine)
                                            ->setLogStartingClass($this->logStartingClass)
                                            ->setLogStartingFunction($this->logStartingFunction);
    }

    /**
     * @param string $sql
     * @param array $params
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function getPreparedStatementQuery(&$sql, $params = [])
    {
        if (class_exists(d3database::class)
            && method_exists(d3database::class, 'getPreparedStatementQuery')
            && is_array($params)
            && count($params)
            && ($query = d3database::getInstance()->getPreparedStatementQuery($sql, $params))
            && strlen(trim($query))
        ) {
            $sql = $query;
        }
    }

    /**
     * @inheritDoc
     */
    public function stopQuery()
    {
        if ($this->SQLQuery) {
            $formatter = new Formatter();

            Monolog\Registry::sql()->addDebug(
                '['.$this->SQLQuery->getReadableElapsedTime().'] ' . ( $this->message ? $this->message : $this->SQLQuery->getSql() ),
                [
                    'query' => $formatter->format($this->SQLQuery->getSql()),
                    'params' => $this->SQLQuery->getParams(),
                    'time' => $this->SQLQuery->getElapsedTime(),
                    'types' => $this->SQLQuery->getTypes(),
                    'logStartingFile' => $this->SQLQuery->getLogStartingFile(),
                    'logStartingLine' => $this->SQLQuery->getLogStartingLine(),
                    'logStartingClass' => $this->SQLQuery->getLogStartingClass(),
                    'logStartingFunction' => $this->SQLQuery->getLogStartingFunction(),
                ]
            );
        }

        $this->SQLQuery = null;
    }
}
