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
    public static function enableLogger($message = null, $file = null, $line = null)
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
        $dbalConfig = $database->getConnection()->getConfiguration();
        $dbalConfig->setSQLLogger(new OxidSQLLogger($message, $file, $line));
    }

    /**
     * @return OxidSQLLogger
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public static function getLogger()
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
        $dbalConfig = $database->getConnection()->getConfiguration();
        return $dbalConfig->getSQLLogger();
    }

    public static function disableLogger()
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
        $dbalConfig = $database->getConnection()->getConfiguration();
        $dbalConfig->setSQLLogger(null);
    }
}
