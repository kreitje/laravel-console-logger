<?php

namespace Kreitje\LaravelConsoleLogger;

trait ConsoleCommandLoggerTrait
{
    protected $consoleCommandLogs = [];

    public function line($string, $style = null, $verbosity = null)
    {
        $logString = $string;
        if (!is_null($style)) {
            $logString = "<{$style}>{$string}</{$style}>";
        }

        $this->consoleCommandLogs[] = $logString;
        parent::line($string, $style, $verbosity);
    }

    public function __destruct()
    {

        if (config('commandlogger.table', '') !== '' && count($this->consoleCommandLogs) > 0) {
            \DB::table(config('commandlogger.table'))->insert([
                'command' => __CLASS__,
                'signature' => $this->signature,
                'output' => join("\r\n", $this->consoleCommandLogs),
                'created_at' => now()
            ]);
        }
    }
}
