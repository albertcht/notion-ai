<?php

namespace AlbertCht\NotionAi;

use AlbertCht\NotionAi\Concerns\HasPrompts;
use AlbertCht\NotionAi\Concerns\HasTopics;
use AlbertCht\NotionAi\Contracts\StreamHandlerContract;
use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;
use Ramsey\Uuid\Uuid;

class NotionAi
{
    use HasPrompts;
    use HasTopics;

    const BASE_URI = 'https://www.notion.so/api/v3';

    protected ClientInterface $client;
    protected $streamHandler;

    protected string $model = 'openai-3';
    protected string $token;
    protected string $spaceId;
    protected $streamCallback;
    protected int $bufferSize = 128;

    public function __construct(string $token, string $spaceId, array $options = [])
    {
        $this->token = $token;
        $this->spaceId = $spaceId;
        $this->client = new Client(array_merge([
            'timeout' => 0,
            'stream' => true,
        ], $options));
    }

    public function setClient(ClientInterface $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    public function setBufferSize(int $bufferSize): self
    {
        $this->bufferSize = $bufferSize;

        return $this;
    }

    public function getBufferSize(): int
    {
        return $this->bufferSize;
    }

    public function setStreamHandler(StreamHandlerContract $streamHandler): self
    {
        $this->streamHandler = $streamHandler;

        return $this;
    }

    public function getStreamHandler(): StreamHandlerContract
    {
        if ($this->streamHandler) {
            return $this->streamHandler;
        }

        return $this->streamHandler = new StreamHandler($this->bufferSize);
    }

    public function stream(callable $callback, ?int $bufferSize = null): self
    {
        $this->streamCallback = $callback;
        if ($bufferSize) {
            $this->bufferSize = $bufferSize;
        }

        return $this;
    }

    public function sendRequest(array $context): ?string
    {
        $stream = $this->client->post(static::BASE_URI . '/getCompletion', [
            'headers' => [
                'Cookie' => "token_v2={$this->token}",
            ],
            'json' => [
                'id' => Uuid::uuid4(),
                'model' => $this->model,
                'spaceId' => $this->spaceId,
                'isSpacePermission' => false,
                'context' => $context,
            ],
        ])->getBody();

        $result = null;
        $this->getStreamHandler()
            ->handle($stream, function ($output) use (&$result) {
                $result .= $output;
                if ($this->streamCallback) {
                    $this->streamCallback($output);
                }
            });

        return $result;
    }
}
