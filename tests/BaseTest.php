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
        dd($downloader->getDownloadLinks('https://www.youtube.com/watch?v=_ojDgx__IMw'));
    }
}