<?php

/**
 * @author    Tobias Matthaiou <developer@tobimat.eu>
 * @author    D3 Data Development - Daniel Seifert <support@shopmodule.com>
 */

declare(strict_types=1);

use D3\OxidSqlLogger\OxidEsalesDatabase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * @param string|null $message
 *
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function D3StartSQLLog(string $message = null): void
{
    /** @var OxidEsalesDatabase $database */
    $database = oxNew(OxidEsalesDatabase::class);
    $database->d3EnableLogger($message);
}

/**
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function D3StopSQLLog(): void
{
    /** @var OxidEsalesDatabase $database */
    $database = oxNew(OxidEsalesDatabase::class);
    $database->d3DisableLogger();
}

/**
 * @param string $message
 *
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function D3AddSQLLogItem(string $message): void
{
    /** @var OxidEsalesDatabase $database */
    $database = oxNew(OxidEsalesDatabase::class);
    if ($logger = $database->d3GetLogger()) {
        $logger->startQuery($message);
        $logger->stopQuery();
    }
}
