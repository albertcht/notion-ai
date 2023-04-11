<?php

use AlbertCht\NotionAi\Exceptions\NotionAiException;
use AlbertCht\NotionAi\StreamHandler;
use GuzzleHttp\Psr7\Utils;

it('can handle stream response', function () {
    $stream = Utils::streamFor(<<< EOF
        {"type":"success","completion":"hello"}\n
        {"type":"success","completion":" world, "}\n
        {"type":"success","completion":"this is "}\n
        {"type":"success","completion":"a "}\n
        {"type":"success","completion":"stream response."}\n
    EOF);

    $handler = new StreamHandler(32);
    $result = null;
    $handler->handle($stream, function ($output) use (&$result) {
        $result .= $output;
    });

    $this->assertSame('hello world, this is a stream response.', $result);
});

it('will throw exception when stream response is failed', function () {
    $stream = Utils::streamFor('{"type":"error","message":"Something went wrong (error code 403).","errorCode":403}');

    $handler = new StreamHandler(32);

    $this->expectException(NotionAiException::class);
    $this->expectExceptionMessage('Something went wrong (error code 403).');

    $handler->handle($stream, function ($output) {
        return;
    });
});
