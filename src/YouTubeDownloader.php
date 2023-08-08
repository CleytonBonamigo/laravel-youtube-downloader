<?php

namespace CleytonBonamigo\LaravelYoutubeDownloader;

use CleytonBonamigo\LaravelYoutubeDownloader\Exceptions\TooManyRequestsException;
use CleytonBonamigo\LaravelYoutubeDownloader\Exceptions\VideoNotFoundException;
use CleytonBonamigo\LaravelYoutubeDownloader\Exceptions\YouTubeException;
use CleytonBonamigo\LaravelYoutubeDownloader\Models\YouTubeConfigData;
use CleytonBonamigo\LaravelYoutubeDownloader\Responses\PlayerApiResponse;
use CleytonBonamigo\LaravelYoutubeDownloader\Responses\WatchVideoPage;
use CleytonBonamigo\LaravelYoutubeDownloader\Utils\Utils;

class YouTubeDownloader
{
    protected BrowserClient $client;
    protected string $videoId;

    public function __construct()
    {
        $this->client = new BrowserClient();
    }

    /**
     * @return WatchVideoPage
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPage(): WatchVideoPage
    {
        // exact params as used by youtube-dl... must be there for a reason
        $url = "https://www.youtube.com/watch?" . http_build_query([
            'v' => $this->videoId,
            'gl' => 'US',
            'hl' => 'en',
            'has_verified' => 1,
            'bpctr' => 9999999999
        ]);
        $response = $this->client->get($url, [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3'
            ]
        ]);

        return new WatchVideoPage($response, $url);
    }

    /**
     * @param YouTubeConfigData $configData
     * @return PlayerApiResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function getPlayerApiResponse(YouTubeConfigData $configData): PlayerApiResponse
    {
        // exact params matter, because otherwise "slow" download links will be returned
        $response = $this->client->post("https://www.youtube.com/youtubei/v1/player?key={$configData->getApiKey()}", [
            'json' => [
                'context' => [
                    'client' => [
                        'clientName' => 'WEB',
                        'clientVersion' => '2.20210721.00.00',
                        'hl' => 'en'
                    ]
                ],
                'videoId' => $this->videoId,
                'playbackContext' => [
                    'contentPlaybackContext' => [
                        'html5Preference' => 'HTML5_PREF_WANTS'
                    ]
                ],
                'contentCheckOk' => true,
                'racyCheckOk' => true
            ],
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
                'Content-Type' => 'application/json',
                'X-Goog-Visitor-Id' => $configData->getGoogleVisitorId(),
                'X-Youtube-Client-Name' => $configData->getClientName(),
                'X-Youtube-Client-Version' => $configData->getClientVersion()
            ]
        ]);

        return new PlayerApiResponse($response);
    }

    /**
     * @param string $videoUrl
     * @param array $options
     * @return void
     * @throws TooManyRequestsException
     * @throws VideoNotFoundException
     * @throws YouTubeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadLinks(string $videoUrl, array $options = [])
    {
        $this->videoId = Utils::extractVideoId($videoUrl);
        $page = $this->getPage();

        if($page->isTooManyRequests()){
            throw new TooManyRequestsException();
        }else if(!$page->isStatusOk()){
            throw new YouTubeException('Page failed to load. HTTP error: '.$page->getResponseBody());
        }else if($page->isVideoNotFound()){
            throw new VideoNotFoundException();
        }

        $youTubeConfigData = $page->getYouTubeConfigData();

        // the most reliable way of fetching all download links no matter what
        $playerResponse = $this->getPlayerApiResponse($youTubeConfigData);
        $playerUrl = $page->getPlayerScriptUrl();

        $response = $this->client->getStorageDirectory();
        dd($playerUrl, $response);
    }
}
