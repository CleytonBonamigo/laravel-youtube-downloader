<?php

namespace CleytonBonamigo\LaravelYoutubeDownloader;

use GuzzleHttp\Client;

class BrowserClient extends Client
{
    /** @var string|null $storageDir */
    protected ?string $storageDir = null;

    /** @var string */
    protected string $version = 'v3';

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
     * Returns the version
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
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

    /**
     * @param string $url
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCached(string $url): string
    {
        $cachePath = sprintf('%s/%s', $this->getStorageDirectory(), $this->getCacheKey($url));

        if (file_exists($cachePath)){
            return unserialize(file_get_contents($cachePath));
        }

        // Disable errors to handle outside
        $response = $this->get($url, ['http_errors' => false]);
        $content = $response->getBody()->getContents();

        if($response->getStatusCode() === 200){
            file_put_contents($cachePath, serialize($content));
        }

        return $content;
    }

    /**
     * Get cache key to mount the cachePath
     *
     * @param $url
     * @return string
     */
    protected function getCacheKey($url): string
    {
        return md5($url) . '_v3';
    }
}
