<?php

namespace JustinKluever\DiscordWebhookBuilder\Support\Webhook;

use DateInterval;
use DateMalformedIntervalStringException;
use DateTimeImmutable;
use JsonSerializable;
use JustinKluever\DiscordWebhookBuilder\Enums\Support\PollLayoutType;
use JustinKluever\DiscordWebhookBuilder\Support\Components\DiscordEmoji;
use Stringable;

/**
 * @phpstan-type PollMediaObject array{ poll_media: array{ text?: string, emoji?: DiscordEmoji } }
 */
class Poll implements JsonSerializable
{
    /**
     * @var PollMediaObject[]
     */
    protected array $answers = [];

    /**
     * @var positive-int|null
     */
    protected ?int $duration = null;

    protected ?bool $allowMultiSelect = null;

    protected ?PollLayoutType $layoutType = null;

    public static function make(?string $question = null): self
    {
        return new self($question);
    }

    private function __construct(protected ?string $question = null) {}

    /**
     * See {@see https://docs.discord.com/developers/resources/poll#poll-create-request-object Discord Poll Object documentation}
     * for whatever the current limit is
     */
    public function answer(string $answer, ?DiscordEmoji $emoji = null): static
    {
        $this->answers[] = [
            'poll_media' => array_filter([
                'text' => $answer,
                'emoji' => $emoji,
            ], static fn (mixed $v): bool => $v !== null),
        ];

        return $this;
    }

    /**
     * Duration in hours (min. 1).
     *
     * can accept string based intervals: `2 hours`, `1 day`, `90 minutes`, `1 week`,
     * will ceil interval to next hour.
     *
     * See {@see https://docs.discord.com/developers/resources/poll#poll-create-request-object Discord Poll Object documentation}
     * for whatever the current limit is
     *
     * @param  DateInterval|positive-int|Stringable|string|null  $hours
     */
    public function duration(DateInterval|int|Stringable|string|null $hours = 1): static
    {
        if (is_string($hours) || $hours instanceof Stringable) {
            // it should only throw after 8.3 but i play it safe just in case
            try {
                $interval = DateInterval::createFromDateString((string) $hours);
                $hours = $interval instanceof DateInterval ? $interval : null;
            } catch (DateMalformedIntervalStringException) {
                $hours = null;
            }
        }

        if ($hours instanceof DateInterval) {
            $hours = (int) ceil(
                (new DateTimeImmutable('@0'))->add($hours)->getTimestamp() / 3600
            );
        }

        $this->duration = max(1, $hours ?? 1);

        return $this;
    }

    public function getDuration(): int
    {
        return $this->duration ?? 1;
    }

    public function multiSelect(?bool $multiSelect = true): static
    {
        $this->allowMultiSelect = $multiSelect;

        return $this;
    }

    public function getMultiSelect(): bool
    {
        return $this->allowMultiSelect ?? false;
    }

    public function layout(?PollLayoutType $layout = null): static
    {
        $this->layoutType = $layout;

        return $this;
    }

    public function getLayout(): PollLayoutType
    {
        return $this->layoutType ?? PollLayoutType::DEFAULT;
    }

    public function jsonSerialize(): mixed
    {
        return array_filter([
            'question' => ['text' => $this->question],
            'answers' => $this->answers,
            'duration' => $this->duration,
            'allow_multiselect' => $this->allowMultiSelect,
            'layout_type' => $this->layoutType?->value,
        ], static fn (mixed $v): bool => $v !== null);
    }
}
