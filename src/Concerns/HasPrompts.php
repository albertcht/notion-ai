<?php

namespace AlbertCht\NotionAi\Concerns;

use AlbertCht\NotionAi\Enums\Languages;
use AlbertCht\NotionAi\Enums\PromptTypes;
use AlbertCht\NotionAi\Enums\Tones;
use AlbertCht\NotionAi\Exceptions\InvalidEnumException;
use AlbertCht\NotionAi\Exceptions\InvalidPromptException;

trait HasPrompts
{
    protected array $customPromptTypes = [
        PromptTypes::HELP_ME_WRITE,
        PromptTypes::HELP_ME_EDIT,
        PromptTypes::HELP_ME_DRAFT,
        PromptTypes::CONTINUE_WRITING,
        PromptTypes::TRANSLATE,
        PromptTypes::CHANGE_TONE,
    ];

    public function writeWithPrompt(string $promptType, string $selectedText, string $pageTitle): ?string
    {
        $this->validatePromptType($promptType);

        return $this->sendRequest([
            'type' => $promptType,
            'pageTitle' => $pageTitle,
            'selectedText' => $selectedText,
        ]);
    }

    public function continueWriting(string $previousContent, string $pageTitle = '', string $restContent = ''): ?string
    {
        return $this->sendRequest([
            'type' => PromptTypes::CONTINUE_WRITING,
            'pageTitle' => $pageTitle,
            'previousContent' => $previousContent,
            'restContent' => $restContent,
        ]);
    }

    public function helpMeEdit(string $prompt, string $selectedText, string $pageTitle = ''): ?string
    {
        return $this->sendRequest([
            'type' => PromptTypes::HELP_ME_EDIT,
            'prompt' => $prompt,
            'pageTitle' => $pageTitle,
            'selectedText' => $selectedText,
        ]);
    }

    public function helpMeWrite(string $prompt, string $previousContent, string $pageTitle = '', string $restContent = ''): ?string
    {
        return $this->sendRequest([
            'type' => PromptTypes::HELP_ME_WRITE,
            'prompt' => $prompt,
            'pageTitle' => $pageTitle,
            'previousContent' => $previousContent,
            'restContent' => $restContent,
        ]);
    }

    public function helpMeDraft(string $prompt, string $previousContent, string $pageTitle = '', string $restContent = ''): ?string
    {
        return $this->sendRequest([
            'type' => PromptTypes::HELP_ME_DRAFT,
            'prompt' => $prompt,
            'pageTitle' => $pageTitle,
            'previousContent' => $previousContent,
            'restContent' => $restContent,
        ]);
    }

    public function translate(string $text, string $language): ?string
    {
        $this->validateEnums(Languages::class, $language);

        return $this->sendRequest([
            'type' => PromptTypes::TRANSLATE,
            'text' => $text,
            'language' => $language,
        ]);
    }

    public function changeTone(string $text, string $tone): ?string
    {
        $this->validateEnums(Tones::class, $tone);

        return $this->sendRequest([
            'type' => PromptTypes::CHANGE_TONE,
            'text' => $text,
            'tone' => $tone,
        ]);
    }

    public function summarize(string $selectedText, string $pageTitle = ''): ?string
    {
        return $this->writeWithPrompt(PromptTypes::SUMMARIZE, $selectedText, $pageTitle);
    }

    public function improveWriting(string $selectedText, string $pageTitle = ''): ?string
    {
        return $this->writeWithPrompt(PromptTypes::IMPROVE_WRITING, $selectedText, $pageTitle);
    }

    public function fixSpellingGrammar(string $selectedText, string $pageTitle = ''): ?string
    {
        return $this->writeWithPrompt(PromptTypes::FIX_SPELLING_GRAMMAR, $selectedText, $pageTitle);
    }

    public function explainThis(string $selectedText, string $pageTitle = ''): ?string
    {
        return $this->writeWithPrompt(PromptTypes::EXPLAIN_THIS, $selectedText, $pageTitle);
    }

    public function makeLonger(string $selectedText, string $pageTitle = ''): ?string
    {
        return $this->writeWithPrompt(PromptTypes::MAKE_LONGER, $selectedText, $pageTitle);
    }

    public function makeShorter(string $selectedText, string $pageTitle = ''): ?string
    {
        return $this->writeWithPrompt(PromptTypes::MAKE_SHORTER, $selectedText, $pageTitle);
    }

    public function findActionItems(string $selectedText, string $pageTitle = ''): ?string
    {
        return $this->writeWithPrompt(PromptTypes::FIND_ACTION_ITEMS, $selectedText, $pageTitle);
    }

    public function simplifyLanguage(string $selectedText, string $pageTitle = ''): ?string
    {
        return $this->writeWithPrompt(PromptTypes::SIMPLIFY_LANGUAGE, $selectedText, $pageTitle);
    }

    protected function validatePromptType(string $promptType): void
    {
        $promptTypes = array_diff(
            PromptTypes::getValues(),
            $this->customPromptTypes
        );
        if (! in_array($promptType, $promptTypes)) {
            throw new InvalidPromptException("Invalid prompt type `{$promptType}`, only: " . implode(',', $promptTypes) . ' are suppored.');
        }
    }

    protected function validateEnums(string $class, string $enum, array $suppported = []): void
    {
        $supported = $suppported ?: $class::getValues();
        if (! in_array($enum, $supported)) {
            throw new InvalidEnumException("Invalid value `{$enum}`, only: " . implode(', ', $supported) . ' are suppored.');
        }
    }
}
