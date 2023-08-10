<?php

namespace CleytonBonamigo\LaravelYoutubeDownloader\Utils;

class Utils
{
    /**
     * Extract YouTube video_id from any piece of text
     *
     * @param string $str
     * @return string|bool
     */
    public static function extractVideoId(string $str): string|bool
    {
        if (strlen($str) === 11) {
            return $str;
        }

        if (preg_match('/(?:\/|%3D|v=|vi=)([a-z0-9_-]{11})(?:[%#?&]|$)/ui', $str, $matches)) {
            return $matches[1];
        }

        return false;
    }

    /**
     * Returns the absolute url
     *
     * @param $url
     * @param $domain
     * @return string
     */
    public static function relativeToAbsoluteUrl($url, $domain): string
    {
        $scheme = parse_url($url, PHP_URL_SCHEME);
        $scheme = $scheme ? $scheme : 'http';

        // relative protocol?
        if (strpos($url, '//') === 0) {
            return $scheme . '://' . substr($url, 2);
        } elseif (strpos($url, '/') === 0) {
            // relative path?
            return $domain . $url;
        }

        return $url;
    }
}
