<?php

namespace CleytonBonamigo\LaravelYoutubeDownloader;

use CleytonBonamigo\LaravelYoutubeDownloader\Models\SplitStream;
use CleytonBonamigo\LaravelYoutubeDownloader\Models\StreamFormat;
use CleytonBonamigo\LaravelYoutubeDownloader\Models\VideoDetails;
use CleytonBonamigo\LaravelYoutubeDownloader\Utils\Utils;

class DownloaderResponse
{
    /** @var StreamFormat[] $formats */
    private array $formats;

    /** @var VideoDetails|null */
    private ?VideoDetails $info;

    public function __construct(array $formats, ?VideoDetails $info = null)
    {
        $this->formats = $formats;
        $this->info = $info;
    }

    /**
     * @return StreamFormat[]
     */
    public function getAllFormats()
    {
        return $this->formats;
    }

    /**
     * @return VideoDetails|null
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Retrieves the Video Formats
     * Will not include Videos with Audio
     *
     * @return array
     */
    public function getVideoFormats(): array
    {
        return Utils::arrayFilterReset($this->getAllFormats(), function ($format) {
            /** @var $format StreamFormat */
            return strpos($format->mimeType, 'video') === 0 && empty($format->audioQuality);
        });
    }

    /**
     * Retrieves the Audio Formats
     *
     * @return array
     */
    public function getAudioFormats(): array
    {
        return Utils::arrayFilterReset($this->getAllFormats(), function ($format) {
            /** @var $format StreamFormat */
            return strpos($format->mimeType, 'audio') === 0;
        });
    }

    /**
     * Combine the formats
     *
     * @return array
     */
    public function getCombinedFormats(): array
    {
        return Utils::arrayFilterReset($this->getAllFormats(), function ($format) {
            /** @var $format StreamFormat */
            return strpos($format->mimeType, 'video') === 0 && !empty($format->audioQuality);
        });
    }

    /**
     * Get the First Combined Format
     *
     * @return StreamFormat|null
     */
    public function getFirstCombinedFormat(): ?StreamFormat
    {
        $combined = $this->getCombinedFormats();
        return count($combined) ? $combined[0] : null;
    }

    /**
     * Order from low to high Video Format
     *
     * @return array
     */
    protected function getLowToHighVideoFormats(): array
    {
        $copy = array_values($this->getVideoFormats());
        usort($copy, function ($a, $b) {
            /** @var StreamFormat $a */
            /** @var StreamFormat $b */
            return $a->height - $b->height;
        });

        return $copy;
    }

    /**
     * Order from low to high Audio Format
     *
     * @return array
     */
    protected function getLowToHighAudioFormats(): array
    {
        $copy = array_values($this->getAudioFormats());

        // Just assume higher filesize => higher quality...
        usort($copy, function ($a, $b) {
            /** @var StreamFormat $a */
            /** @var StreamFormat $b */
            return $a->contentLength - $b->contentLength;
        });

        return $copy;
    }

    /**
     * Combined using: ffmpeg -i video.mp4 -i audio.mp3 output.mp4
     *
     * @param $quality
     * @return SplitStream
     */
    public function getSplitFormats($quality = null)
    {
        // sort formats by quality in desc, and high = first, medium = middle, low = last
        $videos = $this->getLowToHighVideoFormats();
        $audio = $this->getLowToHighAudioFormats();

        if ($quality == 'high' || $quality == 'best') {
            return new SplitStream([
                'video' => $videos[count($videos) - 1],
                'audio' => $audio[count($audio) - 1]
            ]);
        } else if ($quality == 'low' || $quality == 'worst') {
            return new SplitStream([
                'video' => $videos[0],
                'audio' => $audio[0]
            ]);
        }

        // something in between!
        return new SplitStream([
            'video' => $videos[floor(count($videos) / 2)],
            'audio' => $audio[floor(count($audio) / 2)]
        ]);
    }
}