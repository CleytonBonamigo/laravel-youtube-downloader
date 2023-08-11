<?php

namespace CleytonBonamigo\LaravelYoutubeDownloader\Tests;

use CleytonBonamigo\LaravelYoutubeDownloader\YouTubeDownloader;
use PHPUnit\Framework\TestCase as BaseTestCase;

class BaseTest extends BaseTestCase
{
    /** @test */
    public function test_get_download_links()
    {
        $downloader = new YouTubeDownloader();
        $downloader->downloadVideo('https://www.youtube.com/watch?v=o5EJLl8h-E8');

        //dd($downloaderResponse->getFirstCombinedFormat());
    }
}