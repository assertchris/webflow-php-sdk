<?php

namespace Gitstore\Webflow\Clients;

use Exception;
use Gitstore\Webflow\Client;
use Gitstore\Webflow\Exceptions\PackageNotInstalledException;
use Gitstore\Webflow\Exceptions\RequestFailedException;
use Gitstore\Webflow\Response;
use GuzzleHttp\Client as Guzzle;

class GuzzleClient extends Client
{
    private $token;
    private $client;

    public function __construct(string $token, BaseGuzzleClient $guzzle = null)
    {
        $this->token = $token;

        if (is_null($guzzle)) {
            if (!class_exists(Guzzle::class)) {
                throw new PackageNotInstalledException("Guzzle is not installed\ncomposer require guzzlehttp/guzzle");
            }

            $this->guzzle = new Guzzle([
                "base_uri" => static::BASE,
            ]);
        }
    }

    protected function request(string $method, string $path, array $data = []): Response
    {
        $parameters = [
            "headers" => [
                "Authorization" => "Bearer {$this->token}",
                "Accept-Version" => static::VERSION,
                "Accept" => "application/json",
                "Content-Type" => "application/json",
            ],
        ];

        if ($method === "POST" || $method === "PUT" || $method === "PATCH") {
            $parameters["body"] = json_encode($data);
        }

        try {
            $response = $this->guzzle->request($method, $path, $parameters);
        } catch (Exception $exception) {
            throw new RequestFailedException($exception->getMessage(), $exception->getCode(), $exception);
        }

        return new Response(
            $response->getHeaders(),
            (string) $response->getBody()
        );
    }
}
