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

namespace D3\OxidSqlLogger\Handler;

use D3\OxidSqlLogger\Extensions\d3FirePHP;
use OxidEsales\Eshop\Core\Registry;

class d3FirePHPHandler extends \Monolog\Handler\AbstractProcessingHandler
{
    const ADD_TRACE = 'addTrace';
    
    protected function write(array $record): void
    {
        $options = Registry::getConfig()->getConfigParam(d3FirePHPOptions);
        $options = isset($options) && is_array($options) ? $options : [];

        $fp = d3FirePHP::getInstance(true);

        if (in_array(self::ADD_TRACE, $options)) {
            $fp->group( $record['message'], [ 'Collapsed' => true ] );
        }

        $fp->log( $record['formatted'], $record['message']);

        if (in_array(self::ADD_TRACE, $options)) {
            $fp->trace( 'trace', 'trace' );
            $fp->groupEnd();
        }
    }
}