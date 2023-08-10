<?php

namespace CleytonBonamigo\LaravelYoutubeDownloader\Responses;

use Illuminate\Support\Arr;

class PlayerApiResponse extends HttpResponse
{
    /**
     * @param string $key
     * @return null|string|array
     */
    protected function query(string $key): mixed
    {
        return Arr::get($this->getJson(), $key);
    }

    /**
     * @return array
     */
    public function getAllFormats(): array
    {
        $formats = $this->query('streamingData.formats');

        // Video only or audio only streams
        $adaptativeFormats = $this->query('streamingData.adaptativeFormats');

        return array_merge((array)$formats, (array)$adaptativeFormats);
    }
}
