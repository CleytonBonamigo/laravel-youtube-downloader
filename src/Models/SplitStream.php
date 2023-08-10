<?php

namespace CleytonBonamigo\LaravelYoutubeDownloader\Models;

class SplitStream extends AbstractModel
{
    /** @var StreamFormat */
    public StreamFormat $video;
    /** @var StreamFormat */
    public StreamFormat $audio;
}