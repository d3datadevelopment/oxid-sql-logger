Oxid eShop SQL Logger
---------------------

Returns all SQL queries into console of a Browser.

## Install

`composer require --dev d3/oxid-sql-logger`

## Usage

Just set the function `D3StartSQLLog()` somewhere and from that point on all SQLs will be logged.

```php
\D3StartSQLLog('specific log message');

$db = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);
$list = $db->getAll('SELECT * FROM oxarticles WHERE oxprice < ? LIMIT 100', [49.99]);

\D3StopSQLLog();
```

## Screenshots

Browser:

![Example all sqls](https://raw.githubusercontent.com/d3datadevelopment/oxid-sql-logger/master/img/screenshot-a.jpg)

CLI:

![Example CLI](https://raw.githubusercontent.com/d3datadevelopment/oxid-sql-logger/master/img/screenshot-cli.jpg)

## Configuration

Add the sections to the config.inc.php of the shop if needed:

```php
$this->SqlLoggerGUIHandlers = [
    \Monolog\Handler\BrowserConsoleHandler::class,
    \D3\OxidSqlLogger\Handler\d3FirePHPHandler::class
];
```

Standard handlers are BrowserConsoleHandler and d3FirePHPHandler.

```php
$this->SqlLoggerCLIHandlers = [
    ...
];
```

Standard handler is StreamHandler.

```php
$this->d3FirePHPOptions = [
    \D3\OxidSqlLogger\Handler\d3FirePHPHandler::ADD_TRACE
];
```

## Credits

Many thanks to [Tobias Matthaiou](https://github.com/TumTum/oxid-sql-logger) for his inspiration.
