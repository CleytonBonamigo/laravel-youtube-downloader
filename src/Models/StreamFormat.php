<?php

namespace CleytonBonamigo\LaravelYoutubeDownloader\Models;

use CleytonBonamigo\LaravelYoutubeDownloader\Utils\VideoExtensions;

class StreamFormat extends AbstractModel
{
    public int $itag;
    public string $mimeType;
    public int $width;
    public int $height;
    public string $contentLength;
    public string $quality;
    public string $qualityLabel;
    public string $audioQuality;
    public string $audioSampleRate;
    public string $url;
    public ?string $signatureCipher;

    /**
     * Cleans the MimeType
     *
     * @return string
     */
    public function getCleanMimeType(): string
    {
        return trim(preg_replace('/;.*/', '', $this->mimeType));
    }

    /**
     * Has the Rate Bypass
     *
     * @return bool
     */
    public function hasRateBypass(): bool
    {
        return str_contains($this->url, 'ratebypass');
    }

    /**
     * Gets the Video Extension to save the file
     *
     * @return string|null
     */
    public function getExtension(): ?string
    {
        return VideoExtensions::getExtension($this->getCleanMimeType());
    }
}