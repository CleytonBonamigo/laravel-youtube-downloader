<?php

namespace CleytonBonamigo\LaravelYoutubeDownloader\Responses;

use CleytonBonamigo\LaravelYoutubeDownloader\Models\YouTubeConfigData;
use CleytonBonamigo\LaravelYoutubeDownloader\Utils\Utils;

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

    /**
     * Look for a player script URL. E.g:
     * <script src="//s.ytimg.com/yts/jsbin/player-fr_FR-vflHVjlC5/base.js" name="player/base"></script>
     *
     * @return string|null
     */
    public function getPlayerScriptUrl(): ?string
    {
        // check what player version that video is using
        if (preg_match('@<script\s*src="([^"]+player[^"]+js)@', $this->getResponseBody(), $matches)) {
            return Utils::relativeToAbsoluteUrl($matches[1], 'https://www.youtube.com');
        }

        return null;
    }
}
