<?php

/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * https://www.d3data.de
 *
 * @copyright (C) D3 Data Development (Inh. Thomas Dartsch)
 * @author    D3 Data Development - Daniel Seifert <support@shopmodule.com>
 * @link      https://www.oxidmodule.com
 */

declare(strict_types=1);

namespace D3\OxidSqlLogger\Extensions;

use D3\OxidSqlLogger\Handler\d3FirePHPHandler;
use D3\OxidSqlLogger\OxidSQLLogger;
use Doctrine\DBAL\Connection;
use Exception;
use FirePHP;
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
    public static function getInstance($autoCreate = false): FirePHP
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
    public static function init(): FirePHP
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
     * @return bool
     * @throws Exception
     */
    public function trace($label): bool
    {
        return $this->fb($label, $label, FirePHP::TRACE, [
            'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS),
        ]);
    }
}
