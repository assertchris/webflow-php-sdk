<?php

namespace Gitstore\Webflow;

final class Response
{
    private $headers;
    private $body;

    public function __construct(array $headers, string $body)
    {
        $this->headers = $headers;
        $this->body = $body;
    }

    public function getRequestLimit(): int
    {
        return (int) $this->headers["X-RateLimit-Limit"][0];
    }

    public function getRequestsRemaining(): int
    {
        return (int) $this->headers["X-RateLimit-Remaining"][0];
    }

    public function getData(): array
    {
        return json_decode($this->body, true);
    }

    public function withBody(string $body)
    {
        $clone = clone $this;
        $clone->body = $body;

        return $clone;
    }
}
