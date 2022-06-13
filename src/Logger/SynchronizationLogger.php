<?php

namespace Connector\Logger;


class SynchronizationLogger
{
    const TYPE_PRODUCT_XML = 200;
    const TYPE_CATALOG_SECTION_XML = 201;

    private int $syncType;

    private string $b2bBasePath;

    private string $phpExec;

    /**
     * SynchronizationLogger constructor.
     */
    public function __construct()
    {
        $this->b2bBasePath = $_ENV['B2B_BASE_PATH'];
        $this->phpExec = $_ENV['PHP_EXEC'];
    }

    /**
     * @param string $command
     * @param array|null $options
     * @param string $message
     */
    public function startProcess(string $command, array $options = null, string $message = '')
    {
        $optionsStr = '';
        if ($options) {
            $optionsFormated = [];
            foreach ($options as $name => $value) {
                array_push($optionsFormated, $name.' => '.$value);
            }
            $optionsStr = implode(', ', $optionsFormated);
        }

        // Example: ./yii synchronization-logger/start-proccess 101 123333 "gateway/product" "" "Start message"
        $commandLine = $this->phpExec. ' yii synchronization-logger/start-proccess '
            .$this->syncType.' '.getmypid().' "'.$command.'" "'.$optionsStr.'" "'.$message.'"';

        $this->run($commandLine);
    }

    /**
     * @param string $message
     */
    public function completeProcess(string $message = '')
    {
        if ($this->syncType) {
            // Example: ./yii synchronization-logger/complete-proccess 123333 "Complete message"
            $commandLine = $this->phpExec. ' yii synchronization-logger/complete-proccess '
                .getmypid().' "'.$message.'"';

            $this->run($commandLine);
        }
    }

    /**
     * @param string $message
     */
    public function error(string $message = '')
    {
        if ($this->syncType) {
            // Example: ./yii synchronization-logger/error 123333 "Error message"
            $commandLine = $this->phpExec. ' yii synchronization-logger/error '
                .getmypid().' "'.$message.'"';

            print_r($commandLine.PHP_EOL);
            $this->run($commandLine);
        }
    }

    /**
     * @param string $message
     */
    public function addMessage(string $message)
    {
        if ($this->syncType) {
            // Example: ./yii synchronization-logger/add-message 123333 "Some message"
            $commandLine = $this->phpExec. ' yii synchronization-logger/add-message '
                .getmypid().' "'.$message.'"';

            $this->run($commandLine);
        }
    }

    /**
     * @param string $command
     */
    private function run(string $command)
    {
        exec('cd ' . $this->b2bBasePath . ' && ' . $command);
    }

    /**
     * @param int $syncType
     */
    public function setSyncType(int $syncType): void
    {
        $this->syncType = $syncType;
    }
}
