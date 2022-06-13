<?php

declare(strict_types=1);

namespace Connector\Command;

use Connector\Logger\SynchronizationLogger;
use Connector\Service\Import\CatalogSectionService;
use Connector\Service\HttpFileLoader;
use Connector\Service\XmlParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CatalogSectionCommand extends Command
{
    use LockableTrait, CommandLogTrait;

    protected static $defaultName = 'connector:catalog-section';

    private CatalogSectionService $catalogSectionService;

    private string $url;

    /**
     * @param CatalogSectionService $catalogSectionService
     */
    public function __construct(CatalogSectionService $catalogSectionService)
    {
        $this->catalogSectionService = $catalogSectionService;
        $this->logger = new SynchronizationLogger();
        $this->url = $_ENV['PRODUCT_FEED_URL'];
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Импорт разделов каталога из фида');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->lock(self::$defaultName)) {
            $output->writeln('The command is already running in another process.');

            return Command::FAILURE;
        }
        $this->logStartProcess($output, SynchronizationLogger::TYPE_CATALOG_SECTION_XML);

        $fileLoader = new HttpFileLoader($this->url);
        $affectedRows = 0;
        try {
            $file = $fileLoader->load();
            $parser = new XmlParser($file->getRealPath());
            foreach ($parser->catalogSectionsIterator() as $xmlData) {
                $this->catalogSectionService->import($xmlData);
                $affectedRows++;
            }
        } catch (\Exception $e) {
            $this->logErrorProcess($output, 'Exception caught: '.$e->getMessage());

            return Command::FAILURE;
        }

        if (file_exists($file->getRealPath())) {
            unlink($file->getRealPath());
        }

        $this->logCompleteProcess($output, $affectedRows);

        $this->release();

        return Command::SUCCESS;
    }
}
