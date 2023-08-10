<?php

namespace CleytonBonamigo\LaravelYoutubeDownloader\Models;

abstract class AbstractModel
{
    public function __construct($array)
    {
        if (is_array($array)) {
            $this->fillFromArray($array);
        }
    }

    /**
     * Fill all properties from array
     * @param $array
     * @return void
     */
    private function fillFromArray($array): void
    {
        foreach ($array as $key => $val) {
            if (property_exists($this, $key)) {
                $this->{$key} = $val;
            }
        }
    }
}