<?php

namespace App\Service\Discord;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * BotRequest Discord API Wrapper
 *
 */
class BotRequest
{

    private const API_URL = 'https://discord.com/api/v';
    private const API_VERSION = 10;
    private const LOG_PREFIX = '[DISCORD_BOT]';

    private string $token;
    private string $path;
    private string $method;
    private PendingRequest $request;
    private Response $response;
    private bool $hasData = false;

    /**
     * BotRequest constructor
     */
    public function __construct()
    {
        $this->token = env('DISCORD_BOT_TOKEN');
        $this->request = Http::withToken($this->token, 'Bot');
    }

    /**
     * Creates a new BotRequest instance
     *
     * @param string $uri
     * @return BotRequest
     */
    public static function make(string $uri): BotRequest
    {
        return (new BotRequest())
            ->path($uri);
    }

    /**
     * set the request path
     *
     * @param string $path
     * @return self
     */
    public function path(string $path): BotRequest
    {
        $base_url = self::API_URL . self::API_VERSION;

        // check for leading slash
        if (strpos($path, '/') != 0) {
            $path = "/" . $path;
        }

        $this->path = $base_url . $path;
        return $this;
    }

    /**
     * set the query string or POST parameters
     *
     * @param array $data
     * @return self
     */
    public function data(array $data): BotRequest
    {
        $this->request = $this->request->withQueryParameters($data);
        $this->hasData = true;
        return $this;
    }

    /**
     * Send a GET request to Discord
     *
     * @param array|null $query_string
     * @return Collection|boolean
     */
    public function get(?array $query_string = []): Collection|bool
    {
        $this->method = 'GET';
        if (!empty($query_string)) {
            $this->data($query_string);
        }

        $this->response = $this->request->get($this->path);

        return $this->handleResponse();
    }

    /**
     * Send a POST request to Discord
     *
     * @param array|null $args
     * @return Collection|boolean
     */
    public function post(?array $args = []): Collection|bool
    {
        $this->method = 'POST';
        if (!empty($args)) {
            $this->data($args);
        }

        // check for posting without data
        if (!$this->hasData) {
            $this->log("POST was attempted with empty body.");
            return false;
        }

        $this->response = $this->request->post($this->path);
        return $this->handleResponse();
    }

    public function put(?array $args = []): bool
    {
        $this->method = 'PUT';
        if (!empty($args)) {
            $this->data($args);
        }

        $this->response = $this->request->put($this->path);
        return $this->handleResponse();
    }

    public function delete(): bool
    {
        $this->method = 'DELETE';
        $this->response = $this->request->delete($this->path);
        return $this->handleResponse();
    }

    /**
     * Safely parse the response and return an appropriate value.
     *   Collection of response data on success
     *   False on failure
     *
     * @return Collection|boolean
     */
    private function handleResponse(): Collection|bool
    {
        $response = $this->response;
        if ($response->failed()) {
            $this->log("Discord API call failed.");
            return false;
        }
        return $response->collect();
    }

    /**
     * Log a warning message with context
     *
     * @param string $message
     * @return void
     */
    private function log(string $message)
    {
        $logMessage = self::LOG_PREFIX . $message;
        Log::warning($logMessage, [
            'method'    => $this->method,
            'uri'       => $this->path,
            'response'  => $this->response
        ]);
    }
}
