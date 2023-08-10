<?php

namespace CleytonBonamigo\LaravelYoutubeDownloader;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

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
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCached(string $url): Response
    {
        $cachePath = sprintf('%s/%s', $this->getStorageDirectory(), $this->getCacheKey($url));

        if (file_exists($cachePath)){
            return new Response(200, [], unserialize(file_get_contents($cachePath)));
        }

        // Disable errors to handle outside
        $response = $this->get($url, ['http_errors' => false]);
        $content = $response->getBody()->getContents();

        if($response->getStatusCode() === 200){
            file_put_contents($cachePath, serialize($content));
        }

        return new Response(200, $response->getHeaders(), $content);
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
