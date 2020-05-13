<?php

/**
 * This Software is the property of Data Development and is protected
 * by copyright law - it is NOT Freeware.
 * Any unauthorized use of this software without a valid license
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 * http://www.shopmodule.com
 *
 * @copyright (C) D3 Data Development (Inh. Thomas Dartsch)
 * @author        D3 Data Development - Daniel Seifert <support@shopmodule.com>
 * @link          http://www.oxidmodule.com
 */

namespace D3\OxidSqlLogger\Extensions;

use D3\OxidSqlLogger\Handler\d3FirePHPHandler;
use D3\OxidSqlLogger\OxidSQLLogger;
use Doctrine\DBAL\Connection;
use \FirePHP;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use OxidEsales\EshopCommunity\Core\Database\Adapter\Doctrine\Database;

class d3FirePHP extends FirePHP
{
    /**
     * Gets singleton instance of FirePHP
     *
     * @param boolean $autoCreate
     * @return FirePHP
     */
    public static function getInstance($autoCreate = false)
    {
        if ($autoCreate === true && !self::$instance) {
            self::init();
        }
        return self::$instance;
    }

    /**
     * Creates FirePHP object and stores it for singleton access
     *
     * @return FirePHP
     */
    public static function init()
    {
        return self::setInstance(new self());
    }

    public function __construct()
    {
        parent::__construct();

        $this->ignoreClassInTraces(d3FirePHP::class);
        $this->ignoreClassInTraces(d3FirePHPHandler::class);
        $this->ignoreClassInTraces(Logger::class);
        $this->ignoreClassInTraces(AbstractProcessingHandler::class);
        $this->ignoreClassInTraces(OxidSQLLogger::class);
        $this->ignoreClassInTraces(Connection::class);
        $this->ignoreClassInTraces(Database::class);
    }

    /**
     * Log a trace in the firebug console
     *
     * @see FirePHP::TRACE
     * @param string $label
     * @return true
     * @throws Exception
     */
    public function trace($label)
    {
        return $this->fb($label, $label, FirePHP::TRACE, array(
            'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)
        ));
    }
}