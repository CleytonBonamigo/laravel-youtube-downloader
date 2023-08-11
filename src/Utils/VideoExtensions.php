<?php

namespace CleytonBonamigo\LaravelYoutubeDownloader\Utils;

class VideoExtensions
{
    const MP4 = 'video/mp4';

    /**
     * Retrieves the file extension for the given video type.
     *
     * @param string $videoTupe
     * @return string|null
     */
    public static function getExtension(string $videoTupe = ''): ?string
    {
        return match ($videoTupe) {
            self::MP4 => '.mp4',
            default => null,
        };
    }
}