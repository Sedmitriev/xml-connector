<?php

declare(strict_types=1);

namespace Connector\Service;

use Symfony\Component\HttpFoundation\File\File;

class HttpFileLoader
{
    private TemporaryFileStorage $temporaryFileStorage;

    private string $url;

    /**
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->temporaryFileStorage = new TemporaryFileStorage();;
        $this->url = $url;
    }

    /**
     * @return File
     */
    public function load(): File
    {
        $newFileName = sprintf('parisnail_%s.%s', uniqid(), 'xml');
        $file = $this->temporaryFileStorage->create($newFileName);

        $context = stream_context_create( array(
            'http' => ['timeout' => 10 * 60.0]
        ));

        $localHandle = @fopen($file->getRealPath(), "w");
        $handle = fopen($this->url, 'r', false, $context);
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                fwrite($localHandle, $buffer);
            }
            fclose($handle);
        }
        fclose($localHandle);

        return $file;
    }
}
