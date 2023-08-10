<?php

namespace CleytonBonamigo\LaravelYoutubeDownloader\Models;

use Illuminate\Support\Arr;

class VideoDetails
{
    protected $videoDetails = array();

    private function __construct($videoDetails)
    {
        $this->videoDetails = $videoDetails;
    }

    /**
     * From `videoDetails` array that appears inside JSON on /watch or /get_video_info pages
     *
     * @param $array
     * @return static
     */
    public static function fromPlayerResponseArray($array): static
    {
        return new static(Arr::get($array, 'videoDetails'));
    }

    /**
     * Get the ID
     *
     * @return array|\ArrayAccess|mixed
     */
    public function getId(): mixed
    {
        return Arr::get($this->videoDetails, 'videoId');
    }

    /**
     * Get the Title
     *
     * @return array|\ArrayAccess|mixed
     */
    public function getTitle(): mixed
    {
        return Arr::get($this->videoDetails, 'title');
    }

    /**
     * Get the Keywords
     *
     * @return array|\ArrayAccess|mixed
     */
    public function getKeywords(): mixed
    {
        return Arr::get($this->videoDetails, 'keywords');
    }

    /**
     * Get the Short Description
     *
     * @return array|\ArrayAccess|mixed
     */
    public function getShortDescription(): mixed
    {
        return Arr::get($this->videoDetails, 'shortDescription');
    }

    /**
     * Get the View Count
     *
     * @return array|\ArrayAccess|mixed
     */
    public function getViewCount(): mixed
    {
        return Arr::get($this->videoDetails, 'viewCount');
    }
}