<?php

namespace AlbertCht\NotionAi\Contracts;

use Psr\Http\Message\StreamInterface;

interface StreamHandlerContract
{
    public function handle(StreamInterface $stream, callable $callback): void;
}
