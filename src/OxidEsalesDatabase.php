<?php

/**
 * @author    Tobias Matthaiou <developer@tobimat.eu>
 * @author    D3 Data Development - Daniel Seifert <support@shopmodule.com>
 */

namespace D3\OxidSqlLogger;

use Doctrine\DBAL\Configuration;

/**
 * Class OxidEsalesDatabase
 * Is a depenction injection Helper Class
 */
class OxidEsalesDatabase extends \OxidEsales\Eshop\Core\Database\Adapter\Doctrine\Database
{
    /**
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function d3EnableLogger($message)
    {
        $trace = debug_backtrace((PHP_VERSION_ID < 50306) ? 2 : DEBUG_BACKTRACE_IGNORE_ARGS);

        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
        $dbalConfig = $database->getConnection()->getConfiguration();
        $dbalConfig->setSQLLogger(
            new OxidSQLLogger(
                isset($trace[1]['file']) ? $trace[1]['file'] : null,
                isset($trace[1]['line']) ? $trace[1]['line'] : null,
                isset($trace[2]['class']) ? $trace[2]['class'] : null,
                isset($trace[2]['function']) ? $trace[2]['function'] : null,
                $message
            )
        );
    }

    /**
     * @return mixed
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function d3GetLogger()
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
        $dbalConfig = $database->getConnection()->getConfiguration();
        return $dbalConfig->getSQLLogger();
    }

    /**
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function d3DisableLogger()
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
        $dbalConfig = $database->getConnection()->getConfiguration();
        $dbalConfig->setSQLLogger(null);
    }
}
