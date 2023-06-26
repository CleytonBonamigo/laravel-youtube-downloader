<?php

namespace CleytonBonamigo\LaravelYoutubeDownloader\Exceptions;

class TooManyRequestsException extends YouTubeException
{
    public function __construct()
    {
        parent::__construct('Too many Requests!', 429);
    }
}
