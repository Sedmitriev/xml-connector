<?php

declare(strict_types=1);

namespace Connector\Command;

use Connector\Logger\SynchronizationLogger;
use Connector\Service\HttpFileLoader;
use Connector\Service\Import\ProductService;
use Connector\Service\XmlParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProductCommand extends Command
{
    use LockableTrait, CommandLogTrait;

    protected static $defaultName = 'connector:product';

    private ProductService $productService;

    private string $url;

    /**
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
        $this->logger = new SynchronizationLogger();
        $this->url = $_ENV['PRODUCT_FEED_URL'];
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setDescription('Импорт товаров из фида');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->lock(self::$defaultName)) {
            $output->writeln('The command is already running in another process.');

            return Command::FAILURE;
        }
        $this->logStartProcess($output, SynchronizationLogger::TYPE_PRODUCT_XML);

        $fileLoader = new HttpFileLoader($this->url);
        $affectedRows = 0;
        try {
            $file = $fileLoader->load();
            $parser = new XmlParser($file->getRealPath());
            foreach ($parser->iterateProductData() as $xmlData) {
                $this->productService->import($xmlData);
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
