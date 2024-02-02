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

namespace D3\OxidSqlLogger\Handler;

use D3\OxidSqlLogger\Extensions\d3FirePHP;
use Exception;
use Monolog\Handler\AbstractProcessingHandler;
use OxidEsales\Eshop\Core\Registry;

class d3FirePHPHandler extends AbstractProcessingHandler
{
    const ADD_TRACE = 'addTrace';

    /**
     * @param array $record
     *
     * @throws Exception
     */
    protected function write(array $record): void
    {
        $options = Registry::getConfig()->getConfigParam('d3FirePHPOptions');
        $options = isset($options) && is_array($options) ? $options : [];

        $fp = d3FirePHP::getInstance(true);

        if (in_array(self::ADD_TRACE, $options)) {
            $fp->group( $record['message'], [ 'Collapsed' => true ] );
        }

        $fp->log( $record['formatted'], $record['message']);

        if (in_array(self::ADD_TRACE, $options)) {
            $fp->trace( 'trace');
            $fp->groupEnd();
        }
    }
}