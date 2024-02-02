<?php

/**
 * @author    Tobias Matthaiou <developer@tobimat.eu>
 * @author    D3 Data Development - Daniel Seifert <support@shopmodule.com>
 */

declare(strict_types=1);

namespace D3\OxidSqlLogger;

class SQLQuery
{
    /**
     * @var float
     */
    private float $start_time;

    /**
     * @var float
     */
    private float $stop_time = 0.0;

    /**
     * @var string
     */
    private string $sql = '';

    private ?array $parameters = null;

    private ?array $parameterTypes = null;
    
    private string $logStartingFile;
    
    private int $logStartingLine;
    
    private string $logStartingClass;
    
    private string $logStartingFunction;

    public function __construct()
    {
        $this->start_time = microtime(true);
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return $this->sql;
    }

    /**
     * @param string $sql
     * @return static
     */
    public function setSql(string $sql): static
    {
        $this->sql = $sql;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getParams(): ?array
    {
        return $this->parameters;
    }

    /**
     * @param array|null $params
     *
     * @return static
     */
    public function setParams(array $params = null): static
    {
        $this->parameters = $params;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getTypes(): ?array
    {
        return $this->parameterTypes;
    }

    /**
     * @param array|null $types
     *
     * @return static
     */
    public function setTypes(array $types = null): static
    {
        $this->parameterTypes = $types;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getLogStartingFile(): string
    {
        return $this->logStartingFile;
    }
    
    /**
     * @param string $file
     * @return static
     */
    public function setLogStartingFile(string $file): static
    {
        $this->logStartingFile = $file;
        return $this;
    }

    public function getLogStartingLine(): int
    {
        return $this->logStartingLine;
    }

    /**
     * @param int $line
     *
     * @return static
     */
    public function setLogStartingLine(int $line): static
    {
        $this->logStartingLine = $line;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogStartingClass(): string
    {
        return $this->logStartingClass;
    }

    /**
     * @param string $classname
     *
     * @return static
     */
    public function setLogStartingClass(string $classname): static
    {
        $this->logStartingClass = $classname;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogStartingFunction(): string
    {
        return $this->logStartingFunction;
    }

    /**
     * @param string $functionname
     *
     * @return static
     */
    public function setLogStartingFunction(string $functionname): static
    {
        $this->logStartingFunction = $functionname;
        return $this;
    }

    /**
     * Statement was cancelled prematurely, an error was thrown.
     *
     * @return static
     */
    public function setCanceled(): static
    {
        $this->start_time = 0.0;
        return $this;
    }

    /**
     * Return elapsed time
     * @return float
     */
    public function getElapsedTime(): float
    {
        if ($this->start_time === 0.0) {
            return 0.0;
        }

        if ($this->stop_time === 0.0) {
            $end_time = microtime(true);
            $this->stop_time = $end_time - $this->start_time;
        }

        return (float)$this->stop_time;
    }

    /**
     * Returns a human-readable elapsed time
     *
     * @return string
     */
    public function getReadableElapsedTime(): string
    {
        return $this->readableElapsedTime($this->getElapsedTime());
    }

    /**
     * Returns a human-readable elapsed time
     *
     * @param  float $microtime
     * @param  string  $format   The format to display (printf format)
     * @param int $round
     * @return string
     */
    protected function readableElapsedTime(float $microtime, string $format = '%.3f%s', int $round = 3): string
    {
        if ($microtime >= 1) {
            $unit = 's';
            $time = round($microtime, $round);
        } else {
            $unit = 'ms';
            $time = round($microtime*1000);

            $format = (string) preg_replace('/(%.\d+f)/', '%d', $format);
        }

        return sprintf($format, $time, $unit);
    }
}
