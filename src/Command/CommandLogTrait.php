<?php

declare(strict_types=1);

namespace Connector\Command;

use Connector\Logger\SynchronizationLogger;
use Symfony\Component\Console\Output\OutputInterface;

trait CommandLogTrait
{
    protected SynchronizationLogger $logger;

    /**
     * @param OutputInterface $output
     * @param int $type
     * @param null|string $startMessage
     * @param null|array $options
     */
    protected function logStartProcess(
        OutputInterface $output,
        int $type,
        string $startMessage = null,
        array $options = null
    ): void
    {
        if (!$startMessage) {
            $startMessage = sprintf(
                '%s synchronization started in %s at %s.',
                static::$defaultName,
                date("Y.m.d"),
                date("H:i:s")
            );
        }
        $output->writeln($startMessage);

        $this->logger->setSyncType($type);
        $this->logger->startProcess(static::$defaultName, $options, $startMessage);
    }

    /**
     * @param OutputInterface $output
     * @param string $errorMessage
     */
    protected function logErrorProcess(OutputInterface $output, string $errorMessage): void
    {
        $this->logger->error($errorMessage);
        $output->writeln($errorMessage);
    }

    /**
     * @param OutputInterface $output
     * @param int|null $affectedRows
     * @param string|null $completeMessage
     */
    protected function logCompleteProcess(
        OutputInterface $output,
        int $affectedRows = null,
        string $completeMessage = null
    ): void
    {
        if (!$completeMessage) {
            $completeMessage = sprintf(
                '%s synchronization completed in %s at %s.',
                static::$defaultName,
                date("Y.m.d"),
                date("H:i:s")
            );
        }
        if (!is_null($affectedRows)) {
            $completeMessage .= sprintf(' Affected rows = %d.', $affectedRows);
        }
        $output->writeln($completeMessage);

        $this->logger->completeProcess($completeMessage);
    }
}
