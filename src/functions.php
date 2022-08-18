<?php

use D3\OxidSqlLogger\OxidEsalesDatabase;
use OxidEsales\Eshop\Core\Exception\DatabaseConnectionException;

/**
 * @author    Tobias Matthaiou <developer@tobimat.eu>
 * @author    D3 Data Development - Daniel Seifert <support@shopmodule.com>
 */

/**
 * @param string $message
 *
 * @throws DatabaseConnectionException
 */
function D3StartSQLLog($message = null) {
    /** @var OxidEsalesDatabase $database */
    $database = oxNew( OxidEsalesDatabase::class);
    $database->d3EnableLogger($message);
}

/**
 * @throws DatabaseConnectionException
 */
function D3StopSQLLog()
{
    /** @var OxidEsalesDatabase $database */
    $database = oxNew( OxidEsalesDatabase::class);
    $database->d3DisableLogger();
}

/**
 * @param $message
 *
 * @throws DatabaseConnectionException
 */
function D3AddSQLLogItem($message)
{
    /** @var OxidEsalesDatabase $database */
    $database = oxNew( OxidEsalesDatabase::class);
    $database->d3GetLogger()->startQuery($message);
    $database->d3GetLogger()->stopQuery();
}
