<?php

namespace CleytonBonamigo\LaravelYoutubeDownloader;

use CleytonBonamigo\LaravelYoutubeDownloader\Models\StreamFormat;
use CleytonBonamigo\LaravelYoutubeDownloader\Responses\PlayerApiResponse;
use CleytonBonamigo\LaravelYoutubeDownloader\Responses\VideoPlayerJs;
use CleytonBonamigo\LaravelYoutubeDownloader\Utils\Utils;
use Illuminate\Support\Arr;

class PlayerResponseParser
{
    /** @var PlayerApiResponse */
    private PlayerApiResponse $response;

    /** @var VideoPlayerJs */
    protected VideoPlayerJs $videoPlayerJs;


    public function __construct(PlayerApiResponse $response)
    {
        $this->response = $response;
    }

    /**
     * Creates from PlayerApiResponse
     *
     * @param PlayerApiResponse $playerApiResponse
     * @return static
     */
    public static function createFrom(PlayerApiResponse $playerApiResponse): static
    {
        return new static($playerApiResponse);
    }

    /**
     * Sets the videoPlayerJs property
     * @param VideoPlayerJs $videoPlayerJs
     * @return void
     */
    public function setPlayerJsResponse(VideoPlayerJs $videoPlayerJs): void
    {
        $this->videoPlayerJs = $videoPlayerJs;
    }

    /**
     * Parses links into StreamFormat classes
     *
     * @return StreamFormat[]
     */
    public function parseLinks(): array
    {
        $formatCombineds = $this->response->getAllFormats();
        $return = [];

        foreach ($formatCombineds as $format){
            // It appear as either "cipher" or "signatureCipher"
            $cipher = Arr::get($format, 'cipher', Arr::get($format, 'signatureCipher'));
            $stream = new StreamFormat($format);

            // Some videos do not need to be encrypted
            if(isset($format['url'])){
                $return[] = $stream;
                continue;
            }

            $cipherArray = Utils::parseQueryString($cipher);
            $url = Arr::get($cipherArray, 'url');
            $sp = Arr::get($cipherArray, 'sp'); // Used to be 'sig'
            $signature = Arr::get($cipherArray, 's');

            if(!empty($this->videoPlayerJs)){
                $decodedSignature = (new SignatureDecoder())->decode($signature, $this->videoPlayerJs->getResponseBody());
                $decodedUrl = "{$url}&{$sp}={$decodedSignature}";
                $stream->url = $decodedUrl;
            }else{
                $stream->url = $url;
            }

            $return[] = $stream;
        }

        return $return;
    }
}