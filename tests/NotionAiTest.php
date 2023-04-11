<?php

use AlbertCht\NotionAi\Contracts\StreamHandlerContract;
use AlbertCht\NotionAi\Enums\PromptTypes;
use AlbertCht\NotionAi\Enums\Tones;
use AlbertCht\NotionAi\Enums\Topics;
use AlbertCht\NotionAi\Exceptions\InvalidEnumException;
use AlbertCht\NotionAi\Exceptions\InvalidPromptException;
use AlbertCht\NotionAi\Exceptions\InvalidTopicException;
use AlbertCht\NotionAi\NotionAi;
use AlbertCht\NotionAi\Tests\Stubs\DummyUuidFactory;
use GuzzleHttp\Psr7\Utils;
use Mockery as m;
use Psr\Http\Client\ClientInterface;
use Ramsey\Uuid\Uuid;

it('can set psr client', function () {
    $client = new NotionAi('token', 'spaceId');
    $client->setClient($mock = m::mock(ClientInterface::class));

    $this->assertSame($mock, $client->getClient());
});

it('can set buffer size', function () {
    $client = new NotionAi('token', 'spaceId');
    $client->setBufferSize($bufferSize = 9999);

    $this->assertSame($bufferSize, $client->getBufferSize());
});

it('can set stream handler', function () {
    $client = new NotionAi('token', 'spaceId');
    $client->setStreamHandler($handler = m::mock(StreamHandlerContract::class));

    $this->assertSame($handler, $client->getStreamHandler());
});

it('can get default stream handler', function () {
    $client = new NotionAi('token', 'spaceId');

    $this->assertInstanceOf(StreamHandlerContract::class, $client->getStreamHandler());
});

it('can send request', function () {
    $client = mockNotionAiClient($context = ['foo' => 'bar']);

    $client->sendRequest($context);
});

it('can send writeWithPrompt request', function () {
    $client = mockNotionAiClient([
        'type' => PromptTypes::SUMMARIZE,
        'pageTitle' => $title = 'title',
        'selectedText' => $selected = 'selected',
    ]);

    $client->writeWithPrompt(PromptTypes::SUMMARIZE, $selected, $title);
});

it('will throw exception when send writeWithprompt request with wrong type', function () {
    $client = new NotionAi($token = 'token', $spaceId = 'spaceId');

    $this->expectException(InvalidPromptException::class);

    $client->writeWithPrompt('testPrompt', 'text', 'english');
});

it('can send continueWriting request', function () {
    $client = mockNotionAiClient([
        'type' => PromptTypes::CONTINUE_WRITING,
        'pageTitle' => $title = 'title',
        'previousContent' => $previous = 'previous',
        'restContent' => $rest = 'rest',
    ]);

    $client->continueWriting($previous, $title, $rest);
});

it('can send helpMeEdit request', function () {
    $client = mockNotionAiClient([
        'type' => PromptTypes::HELP_ME_EDIT,
        'prompt' => $prompt = 'prompt',
        'selectedText' => $selected = 'selected',
        'pageTitle' => $title = 'title',
    ]);

    $client->helpMeEdit($prompt, $selected, $title);
});

it('can send helpMeWrite request', function () {
    $client = mockNotionAiClient([
        'type' => PromptTypes::HELP_ME_WRITE,
        'prompt' => $prompt = 'prompt',
        'previousContent' => $previous = 'previous',
        'pageTitle' => $title = 'title',
        'restContent' => $rest = 'rest',
    ]);

    $client->helpMeWrite($prompt, $previous, $title, $rest);
});

it('can send helpDraft request', function () {
    $client = mockNotionAiClient([
        'type' => PromptTypes::HELP_ME_DRAFT,
        'prompt' => $prompt = 'prompt',
        'previousContent' => $previous = 'previous',
        'pageTitle' => $title = 'title',
        'restContent' => $rest = 'rest',
    ]);

    $client->helpMeDraft($prompt, $previous, $title, $rest);
});

it('can send translate request', function () {
    $client = mockNotionAiClient([
        'type' => PromptTypes::TRANSLATE,
        'text' => $text = 'text',
        'language' => $language = 'english',
    ]);

    $client->translate($text, $language);
});

it('can send changeTone request', function () {
    $client = mockNotionAiClient([
        'type' => PromptTypes::CHANGE_TONE,
        'text' => $text = 'text',
        'tone' => $tone = Tones::PROFESSIONAL,
    ]);

    $client->changeTone($text, $tone);
});

it('will throw exception when send changeTone request with wrong tone', function () {
    $client = new NotionAi($token = 'token', $spaceId = 'spaceId');

    $this->expectException(InvalidEnumException::class);

    $client->changeTone('text', 'tone');
});

it('can send writeWithTopic request', function () {
    $client = mockNotionAiClient([
        'type' => Topics::OUTLINE,
        'topic' => $prompt = 'prompt',
    ]);

    $client->writeWithTopic(Topics::OUTLINE, $prompt);
});

it('will throw exception when send writeWithTopic request with wrong topic', function () {
    $client = new NotionAi($token = 'token', $spaceId = 'spaceId');

    $this->expectException(InvalidTopicException::class);

    $client->writeWithTopic('testTopic', 'prompt');
});

function mockNotionAiClient(array $context)
{
    $client = new NotionAi($token = 'token', $spaceId = 'spaceId');

    $psrClient = mockGuzzleClient([
        'token' => $token,
        'spaceId' => $spaceId,
        'stream' => $stream = Utils::streamFor('stream data'),
        'context' => $context,
    ]);
    $client->setClient($psrClient);

    $handler = m::mock(StreamHandlerContract::class);
    $handler->shouldReceive('handle')
        ->with($stream, m::type('callable'));
    $client->setStreamHandler($handler);

    return $client;
}

function mockGuzzleClient(array $parameters)
{
    $uuidFactory = new DummyUuidFactory();
    $uuidFactory->uuid4 = Uuid::fromString($parameters['uuid'] ?? 'eed957cf-2f29-4e32-bd2b-dd6b4881e8b1');
    Uuid::setFactory($uuidFactory);

    $token = $parameters['token'] ?? 'token';
    $psrClient = m::mock(ClientInterface::class);
    $psrClient->shouldReceive('post')
        ->with(
            NotionAi::BASE_URI . '/getCompletion',
            [
                'headers' => [
                    'Cookie' => "token_v2={$token}",
                ],
                'json' => [
                    'id' => $uuidFactory->uuid4,
                    'model' => 'openai-3',
                    'spaceId' => $parameters['spaceId'] ?? 'spaceId',
                    'isSpacePermission' => false,
                    'context' => $parameters['context'] ?? ['foo' => 'bar'],
                ],
            ]
        )->once()
        ->andReturnSelf()
        ->shouldReceive('getBody')
        ->once()
        ->andReturn($parameters['stream'] ?? Utils::streamFor('stream data'));

    return $psrClient;
}
