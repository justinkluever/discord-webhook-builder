<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder;

use InvalidArgumentException;
use JsonException;
use JsonSerializable;
use JustinKluever\DiscordWebhookBuilder\Concerns\Support\InteractsWithBitmasks;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Component;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\IsComponentsV2;
use JustinKluever\DiscordWebhookBuilder\Enums\Support\MessageFlag;
use JustinKluever\DiscordWebhookBuilder\Support\Components\UnfurledMediaItem;
use JustinKluever\DiscordWebhookBuilder\Support\Webhook\AllowedMentions;
use JustinKluever\DiscordWebhookBuilder\Support\Webhook\Embed;
use JustinKluever\DiscordWebhookBuilder\Support\Webhook\Poll;
use RuntimeException;
use Stringable;

class Webhook implements JsonSerializable, Stringable
{
    use InteractsWithBitmasks;

    /**
     * Webhook usernames are not allowed to contain these items
     */
    public const array BLACKLISTED_USERNAME_SUBSTRINGS = ['```', 'discord', 'clyde', 'wumpus', 'system message'];

    /**
     * Webhook usernames must not be one of these (exactly)
     */
    public const array BLACKLISTED_USERNAMES = ['everyone', 'here'];

    protected ?string $content = null;

    protected ?string $username = null;

    protected ?string $avatarUrl = null;

    protected ?bool $tts = null;

    /**
     * @var Embed[]
     */
    protected array $embeds = [];

    /**
     * @var Component[]
     */
    protected array $components = [];

    protected ?int $flags = null;

    protected ?string $threadName = null;

    /**
     * @var string[]|int[]
     */
    protected array $tags = [];

    protected ?AllowedMentions $allowedMentions = null;

    protected ?Poll $poll = null;

    public static function make(): self
    {
        return new self;
    }

    /**
     * the message contents (up to 2000 characters)
     */
    public function content(Stringable|string $content): static
    {
        $content = (string) $content;

        if (mb_strlen($content) > 2000) {
            throw new InvalidArgumentException('Content must not exceed 2000 characters.');
        }

        $this->content = $content;

        return $this;
    }

    /**
     * override the default username of the webhook (1 to 80 characters)
     */
    public function username(Stringable|string $username): static
    {
        $username = trim((string) $username);

        if ($username === '' || mb_strlen($username) > 80) {
            throw new InvalidArgumentException('Username must be between 1 and 80 characters.');
        }

        $lowered = mb_strtolower($username);

        if (in_array($lowered, self::BLACKLISTED_USERNAMES, true)) {
            throw new InvalidArgumentException(sprintf(
                'Username "%s" is not allowed.',
                $username,
            ));
        }

        foreach (self::BLACKLISTED_USERNAME_SUBSTRINGS as $substring) {
            if (str_contains($lowered, $substring)) {
                throw new InvalidArgumentException(sprintf(
                    'Username must not contain "%s".',
                    $substring,
                ));
            }
        }

        $this->username = $username;

        return $this;
    }

    /**
     * override the default avatar of the webhook
     */
    public function avatarUrl(UnfurledMediaItem|Stringable|string $url): static
    {
        $this->avatarUrl = $url instanceof UnfurledMediaItem
            ? $url->getUrl()
            : (string) $url;

        return $this;
    }

    /**
     * annoy users with this webhook (text to speech)
     */
    public function tts(bool $tts = true): static
    {
        $this->tts = $tts;

        return $this;
    }

    /**
     * Add Components to this webhook (Components V2 Flag required)
     */
    public function component(Component ...$components): static
    {
        foreach ($components as $component) {
            $this->components[] = $component;
        }

        return $this;
    }

    /**
     * Add `rich` Embeds to this webhook (not allowed with Components V2 flag)
     */
    public function embed(Embed ...$embeds): static
    {
        foreach ($embeds as $embed) {
            $this->embeds[] = $embed;
        }

        return $this;
    }

    public function flag(MessageFlag ...$flags): static
    {
        $invalid = array_filter($flags, static fn (MessageFlag $flag): bool => ! $flag->canBeUsedInWebhook());

        if ($invalid !== []) {
            throw new InvalidArgumentException(sprintf(
                'Following flags cannot be used in a webhook message: %s.',
                implode(', ', array_map(static fn (MessageFlag $flag): string => $flag->name, $invalid)),
            ));
        }

        foreach ($flags as $flag) {
            $this->flags = ($this->flags ?? 0) | $flag->value;
        }

        return $this;
    }

    /**
     * name of thread to create (requires the webhook channel to be a forum or media channel)
     */
    public function threadName(Stringable|string $name): static
    {
        $this->threadName = (string) $name;

        return $this;
    }

    /**
     * array of tag ids to apply to the thread (requires the webhook channel to be a forum or media channel)
     */
    public function tags(string|int ...$tagIds): static
    {
        foreach ($tagIds as $tagId) {
            $this->tags[] = $tagId;
        }

        return $this;
    }

    /**
     * allowed mentions for the message, **will mention user ids by default**
     * 
     * Use {@see AllowedMentions::none()} to suppress all default mentions.
     */
    public function allowedMentions(AllowedMentions $allowedMentions): static
    {
        $this->allowedMentions = $allowedMentions;

        return $this;
    }

    public function poll(?Poll $poll): static
    {
        $this->poll = $poll;

        return $this;
    }

    /**
     * @return array{
     *     content?: string,
     *     username?: string,
     *     avatar_url?: string,
     *     tts?: bool,
     *     embeds?: array<mixed>,
     *     allowed_mentions?: AllowedMentions,
     *     components?: Component[],
     *     flags?: int,
     *     thread_name?: string,
     *     applied_tags?: array<string|int>,
     *     poll?: Poll
     * }
     */
    public function jsonSerialize(): array
    {
        $isV2 = $this->hasFlag(MessageFlag::IS_COMPONENTS_V2);

        $hasV2Component = array_filter(
            $this->components,
            static fn (mixed $c): bool => $c instanceof IsComponentsV2
        ) !== [];

        if ($hasV2Component && ! $isV2) {
            throw new RuntimeException(
                'One or more components require the IS_COMPONENTS_V2 flag.'
            );
        }

        if ($isV2 && ($this->content !== null || $this->embeds !== [] || $this->poll instanceof Poll)) {
            throw new RuntimeException(
                'content, embeds and polls are not allowed while IS_COMPONENTS_V2 flag is set'
            );
        }

        return array_filter([
            'content' => $this->content,
            'username' => $this->username,
            'avatar_url' => $this->avatarUrl,
            'tts' => $this->tts ?: null,
            'embeds' => $this->embeds ?: null,
            'allowed_mentions' => $this->allowedMentions,
            'components' => $this->components ?: null,
            'flags' => $this->flags,
            'thread_name' => $this->threadName,
            'applied_tags' => $this->tags ?: null,
            'poll' => $this->poll ?: null,
        ], static fn (mixed $v): bool => $v !== null);
    }

    protected function getBitmaskEnumClass(): string
    {
        return MessageFlag::class;
    }

    /**
     * @throws JsonException
     */
    public function __toString(): string
    {
        return json_encode($this->jsonSerialize(), JSON_THROW_ON_ERROR);
    }
}
