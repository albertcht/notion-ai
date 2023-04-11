<?php

namespace AlbertCht\NotionAi\Concerns;

use AlbertCht\NotionAi\Enums\Topics;
use AlbertCht\NotionAi\Exceptions\InvalidTopicException;

trait HasTopics
{
    public function writeWithTopic(string $topic, string $prompt): ?string
    {
        $this->validateTopic($topic);

        return $this->sendRequest([
            'type' => $topic,
            'topic' => $prompt,
        ]);
    }

    public function brainstormIdeas(string $prompt): ?string
    {
        return $this->writeWithTopic(Topics::BRAINSTORM_IDEAS, $prompt);
    }

    public function outline(string $prompt): ?string
    {
        return $this->writeWithTopic(Topics::OUTLINE, $prompt);
    }

    public function socialMediaPost(string $prompt): ?string
    {
        return $this->writeWithTopic(Topics::SOCIAL_MEDIA_POST, $prompt);
    }

    public function creativeStory(string $prompt): ?string
    {
        return $this->writeWithTopic(Topics::CREATIVE_STORY, $prompt);
    }

    public function poem(string $prompt): ?string
    {
        return $this->writeWithTopic(Topics::POEM, $prompt);
    }

    public function essay(string $prompt): ?string
    {
        return $this->writeWithTopic(Topics::ESSAY, $prompt);
    }

    public function meetingAgenda(string $prompt): ?string
    {
        return $this->writeWithTopic(Topics::MEETING_AGENDA, $prompt);
    }

    public function pressRelease(string $prompt): ?string
    {
        return $this->writeWithTopic(Topics::PRESS_RELEASE, $prompt);
    }

    public function jobDescription(string $prompt): ?string
    {
        return $this->writeWithTopic(Topics::JOB_DESCRIPTION, $prompt);
    }

    public function salesEmail(string $prompt): ?string
    {
        return $this->writeWithTopic(Topics::SALES_EMAIL, $prompt);
    }

    public function recruitingEmail(string $prompt): ?string
    {
        return $this->writeWithTopic(Topics::RECRUITING_EMAIL, $prompt);
    }

    public function prosConsList(string $prompt): ?string
    {
        return $this->writeWithTopic(Topics::PROS_CONS_LIST, $prompt);
    }

    protected function validateTopic(string $topic): void
    {
        $topics = Topics::getValues();
        if (! in_array($topic, $topics)) {
            throw new InvalidTopicException("Invalid topic `{$topic}`, only: " . implode(', ', $topics) . ' are suppored.');
        }
    }
}
