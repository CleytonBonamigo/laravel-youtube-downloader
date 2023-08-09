<?php

namespace CleytonBonamigo\LaravelYoutubeDownloader;

use CleytonBonamigo\LaravelYoutubeDownloader\Responses\PlayerApiResponse;

class PlayerResponseParser
{
    /** @var PlayerApiResponse */
    private PlayerApiResponse $response;

    public function __construct(PlayerApiResponse $response)
    {
        $this->response = $response;
    }

    /**
     * Creates from PlayerApiResponse
     *
     * @param PlayerApiResponse $playerApiResponse
     * @return static
     */
    public static function createFrom(PlayerApiResponse $playerApiResponse): static
    {
        return new static($playerApiResponse);
    }

    public function parseLinks()
    {
        $formatCombineds = $this->response->getAllFormats();
        dd($formatCombineds);
    }
}