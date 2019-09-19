<?php
/**
 * Autor: Tobias Matthaiou <developer@tobimat.eu>
 * Date: 2019-08-20
 * Time: 21:33
 */

namespace tm\oxid\sql\logger;

use Doctrine\DBAL\Logging\SQLLogger;
use Monolog;

/**
 * Class OxidSQLLogger
 * @package tm\oxid\sql\logger
 */
class OxidSQLLogger implements SQLLogger
{
    public $message;
    public $file;
    public $line;

    /**
     * @inheritDoc
     */
    public function __construct($message = null, $file = null, $line = null)
    {
        if (!Monolog\Registry::hasLogger('sql')) {
            Monolog\Registry::addLogger((new LoggerFactory())->create('sql'));
        }

        $this->message = $message;
        $this->file     = $file;
        $this->line     = $line;
    }

    /**
     * @inheritDoc
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        Monolog\Registry::sql()->addDebug(
            $this->message,
            [
                'query' => $sql,
                'params' => $params,
                'types' => $types,
                'calling_file'   => $this->file,
                'calling_line'   => $this->line,
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
