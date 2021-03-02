<?php

/**
 * @author    Tobias Matthaiou <developer@tobimat.eu>
 * @author    D3 Data Development - Daniel Seifert <support@shopmodule.com>
 */

function D3StartSQLLog($message = null) {
    /** @var \D3\OxidSqlLogger\OxidEsalesDatabase $database */
    $database = oxNew(\D3\OxidSqlLogger\OxidEsalesDatabase::class);
    $database->d3EnableLogger($message);
}

function D3StopSQLLog()
{
    /** @var \D3\OxidSqlLogger\OxidEsalesDatabase $database */
    $database = oxNew(\D3\OxidSqlLogger\OxidEsalesDatabase::class);
    $database->d3DisableLogger();
}

function D3AddSQLLogItem($message)
{
    /** @var \D3\OxidSqlLogger\OxidEsalesDatabase $database */
    $database = oxNew(\D3\OxidSqlLogger\OxidEsalesDatabase::class);
    $database->d3GetLogger()->startQuery($message);
    $database->d3GetLogger()->stopQuery($message);
}
