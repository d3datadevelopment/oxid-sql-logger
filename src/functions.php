<?php

/**
 * @author    Tobias Matthaiou <developer@tobimat.eu>
 * @author    D3 Data Development - Daniel Seifert <support@shopmodule.com>
 */

function D3StartSQLLog($message = null) {
    \D3\OxidSqlLogger\OxidEsalesDatabase::enableLogger($message);
}

function D3StopSQLLog() {
    \D3\OxidSqlLogger\OxidEsalesDatabase::disableLogger();
}

// (new \D3\OxidSqlLogger\AutoInstallSmaryPlugin())->runInstall();
