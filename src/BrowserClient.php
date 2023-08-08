<?php

namespace CleytonBonamigo\LaravelYoutubeDownloader;

use GuzzleHttp\Client;

class BrowserClient extends Client
{
    /** @var string|null $storageDir */
    protected ?string $storageDir;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * Returns the storage directory
     *
     * @return string
     */
    public function getStorageDirectory(): string
    {
        return $this->storageDir ?: sys_get_temp_dir();
    }

    /**
     * Set the storage directory
     *
     * @param string $path
     * @return void
     */
    public function setStorageDirectory(string $path): void
    {
        $this->storageDir = $path;
    }
}
