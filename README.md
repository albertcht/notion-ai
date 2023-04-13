# Notion AI API Client for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/albertcht/notion-ai.svg?style=flat-square)](https://packagist.org/packages/albertcht/notion-ai)
[![Tests](https://img.shields.io/github/actions/workflow/status/albertcht/notion-ai/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/albertcht/notion-ai/actions/workflows/tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/albertcht/notion-ai.svg?style=flat-square)](https://packagist.org/packages/albertcht/notion-ai)

<p align="center"><img src="/art/demo.png" alt="Demo Notion AI" width="65%"></p>

---

## About this package

Notion AI is a powerful artificial intelligence feature based on GPT-3 that is part of the productivity app Notion. You can use Notion AI within Notion itself, but there is currently no official API available.

This is an unofficial PHP client for the Notion AI API that makes it easy to interact with Notion AI in your PHP projects.

## Support this package

<a href="https://www.buymeacoffee.com/albertcht" target="_blank"><img src="https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png" alt="Buy Me A Coffee" style="height: 41px !important;width: 174px !important;box-shadow: 0px 3px 2px 0px rgba(190, 190, 190, 0.5) !important;-webkit-box-shadow: 0px 3px 2px 0px rgba(190, 190, 190, 0.5) !important;" ></a>

You can support us by a cup of coffee. An open-source also relies on community's contribution. Any PRs are welcome to help maintain this package.

## Installation

You can install the package via composer:

```bash
composer require albertcht/notion-ai
```

## Usage

* Initialize a Notion AI instance

```php
$notion = new AlbertCht\NotionAi\NotionAi('token', 'spaceId');
```

* Set options of Guzzle client in the third paramter

> See: https://docs.guzzlephp.org/en/stable/request-options.html

```php
$notion = new AlbertCht\NotionAi\NotionAi('token', 'spaceId', ['timeout' => 120]);
```

* Replace a PSR Http Client if needed

```php
$client = new GuzzleHttp\Client();
$notion->setClient($client);
```

* Send a request with general `sendRequest` function

> If some APIs are not wrapped in this package, you can interact with Notion AI by this function

```php
$notion->sendRequest([
    [
        'type' => $promptType,
        'pageTitle' => $pageTitle,
        'selectedText' => $selectedText,
    ]
]);
```

* Send a request with stream response

```php
// you can set buffer size of stream in the second parameter
$notion->stream(function ($output) {
    echo $output;
    if (ob_get_length()) {
        ob_get_flush();
        flush();
    }
}, 128);
$notion->sendRequest([
    [
        'type' => $promptType,
        'pageTitle' => $pageTitle,
        'selectedText' => $selectedText,
    ]
]);
```

* Supported APIs

```php
public function writeWithPrompt(string $promptType, string $selectedText, string $pageTitle = ''): ?string;

public function continueWriting(string $previousContent, string $pageTitle = '', string $restContent = ''): ?string;

public function helpMeEdit(string $prompt, string $selectedText, string $pageTitle = ''): ?string;

public function helpMeWrite(string $prompt, string $previousContent = '', string $pageTitle = '', string $restContent = ''): ?string;

public function helpMeDraft(string $prompt, string $previousContent = '', string $pageTitle = '', string $restContent = ''): ?string;

public function translate(string $text, string $language): ?string;

public function changeTone(string $text, string $tone): ?string;

public function summarize(string $selectedText, string $pageTitle = ''): ?string;

public function improveWriting(string $selectedText, string $pageTitle = ''): ?string;

public function fixSpellingGrammar(string $selectedText, string $pageTitle = ''): ?string;

public function explainThis(string $selectedText, string $pageTitle = ''): ?string;

public function makeLonger(string $selectedText, string $pageTitle = ''): ?string;

public function makeShorter(string $selectedText, string $pageTitle = ''): ?string;

public function findActionItems(string $selectedText, string $pageTitle = ''): ?string;

public function simplifyLanguage(string $selectedText, string $pageTitle = ''): ?string;

public function writeWithTopic(string $topic, string $prompt): ?string;

public function brainstormIdeas(string $prompt): ?string;

public function outline(string $prompt): ?string;

public function socialMediaPost(string $prompt): ?string;

public function creativeStory(string $prompt): ?string;

public function poem(string $prompt): ?string;

public function essay(string $prompt): ?string;

public function meetingAgenda(string $prompt): ?string;

public function pressRelease(string $prompt): ?string;

public function jobDescription(string $prompt): ?string;

public function salesEmail(string $prompt): ?string;

public function recruitingEmail(string $prompt): ?string;

public function prosConsList(string $prompt): ?string;
```

## Enums

There are enums you can refer to when you call APIs:

* [PromptTypes](/src/Enums/PromptTypes.php)
* [Topics](/src/Enums/Topics)
* [Languages](/src/Enums/Languages)
* [Tones](/src/Enums/Tones)

## Testing

```bash
composer test
```

## How to get token and space id from Notion?

* Get token from Notion

![Screenshot of token.](/art/token.jpg)

* Get space id from Notion

![Screenshot of spaceId.](/art/spaceid.jpg)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Albert Chen](https://github.com/albertcht)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
