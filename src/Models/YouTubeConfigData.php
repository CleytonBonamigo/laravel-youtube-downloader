<?php

namespace CleytonBonamigo\LaravelYoutubeDownloader\Models;

use CleytonBonamigo\LaravelYoutubeDownloader\Utils\Utils;

class YouTubeConfigData
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    protected function query(string $key): string
    {
        return Utils::arrayGet($this->data, $key);
    }

    public function getApiKey(): ?string
    {
        return $this->query('INNERTUBE_API_KEY');
    }

    public function getGoogleVisitorId(): ?string
    {
        return $this->query('VISITOR_DATA');
    }

    public function getClientName(): ?string
    {
        return $this->query('INNERTUBE_CONTEXT_CLIENT_NAME');
    }

    public function getClientVersion(): ?string
    {
        return $this->query('INNERTUBE_CONTEXT_CLIENT_VERSION');
    }
}
