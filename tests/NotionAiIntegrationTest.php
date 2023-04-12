<?php

use AlbertCht\NotionAi\Enums\Languages;
use AlbertCht\NotionAi\Enums\Tones;
use AlbertCht\NotionAi\Enums\Types;
use AlbertCht\NotionAi\NotionAi;

$token = getenv('NOTION_AI_TOKEN');
$spaceId = getenv('NOTION_AI_SPACE_ID');

if (! $token || ! $token) {
    beforeEach(function () {
        // do nothing
    })->markTestSkipped('Use `NOTION_AI_TOKEN` and `NOTION_AI_SPACE_ID` env to run integration tests.');
}

afterEach(function () {
    ob_start();
});

$client = new NotionAi($token, $spaceId);

$client->stream(function ($output) {
    echo $output;
    if (ob_get_length()) {
        ob_get_flush();
        flush();
    }
});

it('can generate sentence with continueWriting api', function () use ($client) {
    output('<continueWriting>');
    $result = $client->continueWriting('give me one random quote');

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with helpMeWrite api', function () use ($client) {
    output('<helpMeWrite>');
    $result = $client->helpMeWrite('give me one random quote');

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with helpMeEdit api', function () use ($client) {
    output('<helpMeEdit>');
    $result = $client->helpMeEdit('give me one random quote', '');

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with helpMeDraft api', function () use ($client) {
    output('<helpMeDraft>');
    $result = $client->helpMeDraft('give me one random quote');

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with translate api', function () use ($client) {
    output('<translate>', true);
    $result = $client->translate('你是誰', Languages::ENGLISH);

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with changeTone api', function () use ($client) {
    output('<changeTone>');
    $result = $client->changeTone('Who are you?', Tones::PROFESSIONAL);

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with summarize api', function () use ($client) {
    output('<summarize>');
    $result = $client->summarize('Be the change that you wish to see in the world.');

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with improveWriting api', function () use ($client) {
    output('<improveWriting>');
    $result = $client->improveWriting('Who are you?');

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with fixSpellingGrammar api', function () use ($client) {
    output('<fixSpellingGrammar>');
    $result = $client->fixSpellingGrammar('Where are come from?');

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with explainThis api', function () use ($client) {
    output('<explainThis>');
    $result = $client->explainThis('Be the change that you wish to see in the world.');

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with makeLonger api', function () use ($client) {
    output('<makeLonger>');
    $result = $client->makeLonger('Hi.');

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with makeShorter api', function () use ($client) {
    output('<makeShorter>');
    $result = $client->makeShorter('Hello, how are you doing today? I hope you are doing well.');

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with findActionItems api', function () use ($client) {
    output('<findActionItems>');
    $result = $client->findActionItems('Hi.');

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with simplifyLanguage api', function () use ($client) {
    output('<simplifyLanguage>');
    $result = $client->simplifyLanguage('Be the change that you wish to see in the world.');

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with brainstormIdeas api', function () use ($client) {
    output('<brainstormIdeas>');
    $result = $client->brainstormIdeas('Be the change that you wish to see in the world.');

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with outline api', function () use ($client) {
    output('<outline>');
    $result = $client->outline('Be the change that you wish to see in the world.');

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with socialMediaPost api', function () use ($client) {
    output('<socialMediaPost>');
    $result = $client->socialMediaPost('Be the change that you wish to see in the world.');

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with creativeStory api', function () use ($client) {
    output('<creativeStory>');
    $result = $client->creativeStory('Be the change that you wish to see in the world.');

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with poem api', function () use ($client) {
    output('<poem>');
    $result = $client->poem('Be the change that you wish to see in the world.');

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with essay api', function () use ($client) {
    output('<essay>');
    $result = $client->essay('Be the change that you wish to see in the world.');

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with meetingAgenda api', function () use ($client) {
    output('<meetingAgenda>');
    $result = $client->meetingAgenda('Standup meeting');

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with pressRelease api', function () use ($client) {
    output('<pressRelease>');
    $result = $client->pressRelease('Go exercise');

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with jobDescription api', function () use ($client) {
    output('<jobDescription>');
    $result = $client->jobDescription('Laravel Developer');

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with salesEmail api', function () use ($client) {
    output('<salesEmail>');
    $result = $client->salesEmail('Big Sale for Mac M1 Pro, only 999 USD today.');

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with recruitingEmail api', function () use ($client) {
    output('<recruitingEmail>');
    $result = $client->recruitingEmail('Laravel Developer');

    $this->assertGreaterThan(0, strlen($result));
});

it('can generate sentence with prosConsList api', function () use ($client) {
    output('<prosConsList>');
    $result = $client->prosConsList('Singleton pattern in design patterns');

    $this->assertGreaterThan(0, strlen($result));
});

function output(string $string, bool $newline = false)
{
    echo "\n\033[01;32m{$string}\033[0m";
    if ($newline) {
        echo "\n";
    }
}
