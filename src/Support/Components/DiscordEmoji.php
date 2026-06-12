<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Support\Components;

use JsonSerializable;
use Stringable;

/**
 * @phpstan-type DiscordEmojiShape array{name: string, id?: string, animated?: true}
 */
readonly class DiscordEmoji implements JsonSerializable, Stringable
{
    public function __construct(
        protected string $name,
        protected ?string $id = null,
        protected bool $animated = false
    ) {
        //
    }

    public static function make(
        Stringable|string $emojiOrName,
        Stringable|string|null $id = null,
        bool $animated = false
    ): self {
        return new self(
            (string) $emojiOrName,
            $id !== null ? (string) $id : null,
            $animated
        );
    }

    public static function fromString(string $emojiString): self
    {
        if (preg_match('/^<(?<animated>a)?:(?<name>\w+):(?<id>\d+)>$/', $emojiString, $matches)) {
            return self::make(
                $matches['name'],
                $matches['id'],
                $matches['animated'] === 'a',
            );
        }

        return self::make($emojiString);
    }

    public function __toString(): string
    {
        if ($this->id !== null) {
            $prefix = $this->animated ? 'a' : '';

            return sprintf('<%s:%s:%s>', $prefix, $this->name, $this->id);
        }

        return $this->name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function isAnimated(): bool
    {
        return $this->animated;
    }

    /**
     * @return DiscordEmojiShape
     */
    public function jsonSerialize(): array
    {
        return array_filter([
            'name' => $this->getName(),
            'id' => $this->getId(),
            'animated' => $this->isAnimated() ?: null,
        ], static fn (mixed $v): bool => $v !== null);
    }
}
