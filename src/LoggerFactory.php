<?php

/**
 * @author    Tobias Matthaiou <developer@tobimat.eu>
 * @author    D3 Data Development - Daniel Seifert <support@shopmodule.com>
 */

namespace D3\OxidSqlLogger;

use Monolog;
use OxidEsales\Eshop\Core\Registry;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * Class Factory
 */
class LoggerFactory
{
    /**
     * @param $name
     * @return Monolog\Logger
     */
    public function create($name)
    {
        return new Monolog\Logger($name, $this->getHandlers(), $this->getProcessors());
    }

    /**
     * @return array
     */
    private function getHandlers()
    {
        if (PHP_SAPI == 'cli') {
            $configuredHandlers = Registry::getConfig()->getConfigParam('SqlLoggerCLIHandlers');

            $handlers = (isset($configuredHandlers) && $this->is_iterable($configuredHandlers)) ?
                $this->getInstancesFromHandlerList($configuredHandlers) :
                [
                    $this->getStreamHandler()
                ];
        } else {
            $configuredHandlers = Registry::getConfig()->getConfigParam('SqlLoggerGUIHandlers');

            $handlers = (isset($configuredHandlers) && $this->is_iterable($configuredHandlers)) ?
                $this->getInstancesFromHandlerList($configuredHandlers) :
                [
                    $this->getBrowserConsoleHandler(),
                    $this->getFirePHPHandler()
                ];
        }

        return $handlers;
    }

    /**
     * @param array $classNames
     *
     * @return array
     */
    private function getInstancesFromHandlerList(array $classNames)
    {
        return array_map(
            function($className){
                return new $className();
            },
            $classNames
        );
    }

    /**
     * polyfill for is_iterable() - available from PHP 7.1
     * @param $obj
     *
     * @return bool
     */
    private function is_iterable($obj)
    {
        return function_exists('is_iterable') ?
            is_iterable($obj) :
            is_array($obj) || (is_object($obj) && ($obj instanceof \Traversable));
    }

    /**
     * @return Monolog\Handler\StreamHandler
     */
    private function getStreamHandler()
    {
        $streamHandler = new Monolog\Handler\StreamHandler('php://stderr');

        $channel    = (new OutputFormatterStyle(null, null, ['bold']))->apply('%channel%');
        $level_name = (new OutputFormatterStyle('blue'))->apply('%level_name%');
        $message    = (new OutputFormatterStyle('green'))->apply('%message%');
        $context    = (new OutputFormatterStyle('yellow'))->apply('%context%');
        $newline    = PHP_EOL . str_repeat(' ', 10);

        $ttl_color = "$channel $level_name: $message {$newline} $context {$newline} %extra%" . PHP_EOL;

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
    private function getBrowserConsoleHandler()
    {
        return new Monolog\Handler\BrowserConsoleHandler();
    }

    /**
     * @return Monolog\Handler\FirePHPHandler
     */
    private function getFirePHPHandler()
    {
        return new Monolog\Handler\FirePHPHandler();
    }

    /**
     * @return array
     */
    private function getProcessors()
    {
        return [
            new Monolog\Processor\IntrospectionProcessor(Monolog\Logger::DEBUG, ['D3\\OxidSqlLogger', 'Doctrine\\DBAL\\Connection', 'OxidEsales\\EshopCommunity\\Core\\Database\\Adapter\\Doctrine\\Database']),
            new Monolog\Processor\PsrLogMessageProcessor(),
        ];
    }
}
