<?php

/**
 * @author    Tobias Matthaiou <developer@tobimat.eu>
 * @author    D3 Data Development - Daniel Seifert <support@shopmodule.com>
 */

declare(strict_types=1);

namespace D3\OxidSqlLogger;

use Monolog;
use Monolog\Logger;
use OxidEsales\Eshop\Core\Registry;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class LoggerFactory
{
    /**
     * @param string $name
     *
     * @return Logger
     */
    public function create(string $name): Monolog\Logger
    {
        return new Monolog\Logger($name, $this->getHandlers(), $this->getProcessors());
    }

    /**
     * @return array
     */
    private function getHandlers(): array
    {
        if (PHP_SAPI == 'cli') {
            $configuredHandlers = Registry::getConfig()->getConfigParam('SqlLoggerCLIHandlers');

            $handlers = (isset($configuredHandlers) && is_iterable($configuredHandlers)) ?
                $this->getInstancesFromHandlerList($configuredHandlers) :
                [
                    $this->getStreamHandler()
                ];
        } else {
            $configuredHandlers = Registry::getConfig()->getConfigParam('SqlLoggerGUIHandlers');

            $handlers = (isset($configuredHandlers) && is_iterable($configuredHandlers)) ?
                $this->getInstancesFromHandlerList($configuredHandlers) :
                [
                    $this->getBrowserConsoleHandler(),
                    $this->getFirePHPHandler()
                ];
        }

        return $handlers;
    }

    /**
     * @param iterable $classNames
     *
     * @return array
     */
    private function getInstancesFromHandlerList(iterable $classNames): array
    {
        return array_map(
            function($className){
                return new $className();
            },
            (array) $classNames
        );
    }

    /**
     * @return Monolog\Handler\StreamHandler
     */
    private function getStreamHandler(): Monolog\Handler\StreamHandler
    {
        $streamHandler = new Monolog\Handler\StreamHandler('php://stderr');

        $channel    = (new OutputFormatterStyle(null, null, ['bold']))->apply('%channel%');
        $level_name = (new OutputFormatterStyle('blue'))->apply('%level_name%');
        $message    = (new OutputFormatterStyle('green'))->apply('%message%');
        $context    = (new OutputFormatterStyle('yellow'))->apply('%context%');
        $newline    = PHP_EOL . str_repeat(' ', 10);

        $ttl_color = "$channel $level_name: $message $newline $context $newline %extra%" . PHP_EOL;

        $streamHandler->setFormatter(
            new Monolog\Formatter\LineFormatter(
                $ttl_color,
                null,
                true,
                true
            )
        );

        return $streamHandler;
    }

    /**
     * @return Monolog\Handler\BrowserConsoleHandler
     */
    private function getBrowserConsoleHandler(): Monolog\Handler\BrowserConsoleHandler
    {
        return new Monolog\Handler\BrowserConsoleHandler();
    }

    /**
     * @return Monolog\Handler\FirePHPHandler
     */
    private function getFirePHPHandler(): Monolog\Handler\FirePHPHandler
    {
        return new Monolog\Handler\FirePHPHandler();
    }

    /**
     * @return array
     */
    private function getProcessors(): array
    {
        return [
            new Monolog\Processor\IntrospectionProcessor(
                Monolog\Logger::DEBUG,
                [
                    'D3\\OxidSqlLogger',
                    'Doctrine\\DBAL\\Connection',
                    'OxidEsales\\EshopCommunity\\Core\\Database\\Adapter\\Doctrine\\Database'
                ]
            ),
            new Monolog\Processor\PsrLogMessageProcessor(),
        ];
    }
}
