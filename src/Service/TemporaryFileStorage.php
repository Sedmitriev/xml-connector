<?php

declare(strict_types=1);

namespace Connector\Service;


use RuntimeException;
use Symfony\Component\HttpFoundation\File\File;

class TemporaryFileStorage
{
    const APP_DIR = __DIR__ . '/../..';

    /**
     * @param File $file
     */
    public function delete(File $file): void
    {
        if (
            $file->getPath() === $this->getTmpDir()
            && file_exists($file->getRealPath())
        ) {
            unlink($file->getRealPath());
        }
    }

    /**
     * @param string $fileName
     * @return File
     */
    public function create(string $fileName): File
    {
        $this->checkTmpDirectory();
        $filePath = $this->getTmpDir().DIRECTORY_SEPARATOR.$fileName;
        @touch($filePath);

        return new File($filePath);
    }

    /**
     * Очистка временной директории
     * @param string|null $dir
     */
    public function clearDirectory(?string $dir = null)
    {
        $path = $this->getTmpDir();
        if ($dir) {
            $path .= DIRECTORY_SEPARATOR.$dir;
        }
        $files = glob($path.DIRECTORY_SEPARATOR.'*');
        foreach($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    /**
     * @return string
     */
    public function getTmpDir(): string
    {
        return self::APP_DIR .'/var/tmp';
    }

    /**
     * Проверка времненой директории
     */
    private function checkTmpDirectory(): void
    {
        $dir = $this->getTmpDir();
        if (!is_dir($dir)) {
            $this->mkdir($dir);
        } elseif (!is_writable($dir)) {
            throw new RuntimeException(sprintf('Unable to write in the tmp directory (%s).', $dir));
        }
    }

    /**
     * @param string $dir
     */
    private function mkdir(string $dir)
    {
        if (false === @mkdir($dir, 0777, true) && !is_dir($dir)) {
            throw new RuntimeException(sprintf('Unable to create the tmp directory (%s).', $dir));
        }
    }
}
