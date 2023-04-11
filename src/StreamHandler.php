<?php

namespace AlbertCht\NotionAi;

use AlbertCht\NotionAi\Contracts\StreamHandlerContract;
use AlbertCht\NotionAi\Exceptions\NotionAiException;
use Psr\Http\Message\StreamInterface;

class StreamHandler implements StreamHandlerContract
{
    protected int $bufferSize;

    public function __construct(int $bufferSize = 128)
    {
        $this->bufferSize = $bufferSize;
    }

    public function handle(StreamInterface $stream, callable $callback): void
    {
        $buffer = null;
        $hasValidated = false;
        while (! $stream->eof()) {
            $readline = $buffer . $stream->read($this->bufferSize);
            $buffer = null;
            if (ob_get_length()) {
                ob_get_flush();
                flush();
            }

            $content = explode("\n", trim($readline));
            foreach ($content as $output) {
                if (! $completion = json_decode($output, true)) {
                    $buffer = $output;
                    continue;
                }
                // if completion is failed at the beginning, throw the exception
                if (! $hasValidated) {
                    $this->validateCompletion($completion, $output);
                    $hasValidated = true;
                }
                if (($completion['type'] ?? null) !== 'success') {
                    continue;
                }

                $callback($completion['completion'] ?? null);
            }
        }
    }

    protected function validateCompletion(array $completion, string $output): void
    {
        if (($completion['type'] ?? null) !== 'success') {
            throw new NotionAiException(
                $completion['message'] ?? $output ?: 'Unknwon error'
            );
        }
    }
}
