<?php

/**
 * @author    Tobias Matthaiou <developer@tobimat.eu>
 * @author    D3 Data Development - Daniel Seifert <support@shopmodule.com>
 */

declare(strict_types=1);

namespace D3\OxidSqlLogger;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Logging\SQLLogger;
use OxidEsales\Eshop\Core\Database\Adapter\Doctrine\Database;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\ConnectionProviderInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class OxidEsalesDatabase extends Database
{
    /**
     * @param string|null $message
     *
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function d3EnableLogger(string $message = null): void
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        $this->d3GetConfiguration()->setSQLLogger(
            new OxidSQLLogger(
                $trace[1]['file'] ?? null,
                (int) $trace[1]['line'] ?? null,
                $trace[2]['class'] ?? null,
                $trace[2]['function'] ?? null,
                $message
            )
        );
    }

    /**
     * @return SQLLogger|null
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function d3GetLogger(): ?SQLLogger
    {
        return $this->d3GetConfiguration()->getSQLLogger();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function d3DisableLogger(): void
    {
        $this->d3GetConfiguration()->setSQLLogger();
    }

    /**
     * @return Configuration|null
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function d3GetConfiguration(): ?Configuration
    {
        return ContainerFactory::getInstance()->getContainer()
                        ->get(ConnectionProviderInterface::class)->get()->getConfiguration();
    }
}
