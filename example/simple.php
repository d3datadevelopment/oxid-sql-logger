<?php

declare(strict_types=1);

use OxidEsales\Eshop\Core\DatabaseProvider;

require __DIR__ . '/../../../../source/bootstrap.php';

D3StartSQLLog('Query for 100 items cheaper than 49,99');

$db = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);
$list = $db->getAll('SELECT * '.PHP_EOL.'FROM oxarticles WHERE oxprice < ? LIMIT 100', [49.99]);

D3StopSQLLog();

// or

D3StartSQLLog();

$db = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);
$list = $db->getAll('SELECT * FROM oxarticles WHERE oxprice < ? LIMIT 100', [49.99]);

D3StopSQLLog();
