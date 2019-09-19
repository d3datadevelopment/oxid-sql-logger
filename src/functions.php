<?php
/**
 * Autor: Tobias Matthaiou <developer@tobimat.eu>
 * Date: 2019-08-20
 * Time: 23:11
 */

function StartSQLLog($message = null, $file = null, $line = null) {
    \tm\oxid\sql\logger\OxidEsalesDatabase::enableLogger($message, $file, $line);
}

function StopSQLLog() {
    \tm\oxid\sql\logger\OxidEsalesDatabase::disableLogger();
}
