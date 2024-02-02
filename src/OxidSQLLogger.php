<?php

/**
 * @author    Tobias Matthaiou <developer@tobimat.eu>
 * @author    D3 Data Development - Daniel Seifert <support@shopmodule.com>
 */

declare(strict_types=1);

namespace D3\OxidSqlLogger;

use D3\ModCfg\Application\Model\d3database;
use Doctrine\DBAL\Logging\SQLLogger;
use Monolog;
use NilPortugues\Sql\QueryFormatter\Formatter;

class OxidSQLLogger implements SQLLogger
{
    public string $message;
    public string $logStartingFile;
    public int $logStartingLine;
    public string $logStartingClass;
    public string $logStartingFunction;

    private SQLQuery|null $SQLQuery = null;

    /**
     * @param string      $file
     * @param int         $line
     * @param string      $class
     * @param string      $function
     * @param string|null $message
     */
    public function __construct(string $file, int $line, string $class, string $function, string $message = null)
    {
        if (!Monolog\Registry::hasLogger('sql')) {
            Monolog\Registry::addLogger((new LoggerFactory())->create('sql'));
        }

        $this->message              = $message;
        $this->logStartingFile      = $file;
        $this->logStartingLine      = $line;
        $this->logStartingClass     = $class;
        $this->logStartingFunction  = $function;
    }

    /**
     * @param string     $sql
     * @param array|null $params
     * @param array|null $types
     */
    public function startQuery($sql, ?array $params = null, ?array $types = null): void
    {
        if ($this->SQLQuery) {
            $this->SQLQuery->setCanceled();
            $this->stopQuery();
        }

        $this->getPreparedStatementQuery( $sql, $params ?? [], $types ?? []);

        $this->SQLQuery = (new SQLQuery())->setSql($sql)
            ->setParams($params)
            ->setTypes($types)
            ->setLogStartingFile($this->logStartingFile)
            ->setLogStartingLine($this->logStartingLine)
            ->setLogStartingClass($this->logStartingClass)
            ->setLogStartingFunction($this->logStartingFunction);
    }

    /**
     * @param string $sql
     * @param array  $params
     * @param array  $types
     */
    public function getPreparedStatementQuery(string &$sql, array $params = [], array $types = []): void
    {
        if (class_exists(d3database::class)
            && method_exists(d3database::class, 'getPreparedStatementQuery')
            && count($params)
            && ($query = d3database::getInstance()->getPreparedStatementQuery($sql, $params, $types))
            && strlen(trim($query))
        ) {
            $sql = $query;
        }
    }

    /**
     * @inheritDoc
     */
    public function stopQuery(): void
    {
        if ($this->SQLQuery) {
            $formatter = new Formatter();

            Monolog\Registry::sql()->addDebug(
                '['.$this->SQLQuery->getReadableElapsedTime().'] ' . ($this->message ?: $this->SQLQuery->getSql()),
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
