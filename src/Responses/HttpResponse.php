<?php

namespace CleytonBonamigo\LaravelYoutubeDownloader\Responses;

use GuzzleHttp\Psr7\Response;

abstract class HttpResponse
{
    private Response $response;
    private ?array $json;

    public function __construct(Response $response)
    {
        $this->response = $response;
        $this->json = json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function getResponseBody(): string
    {
        // To be able to read the body again, you need to call ->getBody()->rewind() to rewind the stream to the beginning.
        // Just be aware that it can cause an exception in rare cases, because not all stream types support rewind operation.
        $this->response->getBody()->rewind();
        return $this->response->getBody()->getContents();
    }

    /**
     * @return array|null
     */
    public function getJson(): array|null
    {
        return $this->json;
    }

    /**
     * @return bool
     */
    public function isStatusOk(): bool
    {
        return $this->response->getStatusCode() === 200;
    }
}
