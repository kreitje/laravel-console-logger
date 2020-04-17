<?php

namespace Kreitje\LaravelConsoleLogger;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $handle = parent::execute($input, $output);

        $this->logToDatabase();
        return $handle;
    }

    public function logToDatabase()
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
