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
     * @param $array
     * @param string $key
     * @param string|null $default
     * @return string|null
     */
    public static function arrayGet($array, string $key, string $default = null): ?string
    {
        foreach (explode('.', $key) as $segment){
            if(is_array($array) && array_key_exists($segment, $array)){
                $array = $array[$segment];
            }else{
                $array = $default;
                break;
            }

            return $array;
        }
    }
}
