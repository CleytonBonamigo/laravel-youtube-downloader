<?php

namespace CleytonBonamigo\LaravelYoutubeDownloader\Responses;

use CleytonBonamigo\LaravelYoutubeDownloader\Models\YouTubeConfigData;

class WatchVideoPage extends HttpResponse
{
    /**
     * @return bool
     */
    public function isTooManyRequests(): bool
    {
        return (
            str_contains($this->getResponseBody(), 'We have been receiving a large volume of requests') ||
            str_contains($this->getResponseBody(), 'systems have detected unusual traffic') ||
            str_contains($this->getResponseBody(), '/recaptcha/')
        );
    }

    /**
     * @return bool
     */
    public function isVideoNotFound(): bool
    {
        return str_contains($this->getResponseBody(), '<title> - YouTube</title>');
    }

    /**
     * @return YouTubeConfigData|null
     */
    public function getYouTubeConfigData(): YouTubeConfigData|null
    {
        preg_match('/ytcfg.set\(({.*?})\)/', $this->getResponseBody(), $matches);
        if (preg_match('/ytcfg.set\(({.*?})\)/', $this->getResponseBody(), $matches)) {
            $data = json_decode($matches[1], true);
            return new YouTubeConfigData($data);
        }

        return null;
    }
}
