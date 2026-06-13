<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Support\Webhook;

use DateTimeInterface;
use JsonSerializable;
use JustinKluever\DiscordWebhookBuilder\Concerns\Support\InteractsWithBitmasks;
use JustinKluever\DiscordWebhookBuilder\Contracts\Support\HasColor;
use JustinKluever\DiscordWebhookBuilder\Enums\Embed\EmbedFlag;
use JustinKluever\DiscordWebhookBuilder\Enums\Embed\EmbedType;
use Stringable;

/**
 * @phpstan-type EmbedColor int<0, 16777215>
 * @phpstan-type EmbedFooter array{ text: string, icon_url?: string }
 * @phpstan-type EmbedImage array{ url: string }
 * @phpstan-type EmbedThumbnail array{ url: string }
 * @phpstan-type EmbedAuthor array{ name: string, url?: string, icon_url?: string }
 * @phpstan-type EmbedFields array<int, array{ name: string, value: string, inline?: true }>
 *
 * @see https://docs.discord.com/developers/resources/message#embed-object
 */
class Embed implements JsonSerializable
{
    use InteractsWithBitmasks;

    protected ?string $title = null;

    protected ?string $description = null;

    protected ?string $url = null;

    /**
     * @var EmbedColor|null
     */
    protected ?int $color = null;

    protected ?string $timestamp = null;

    /**
     * @var EmbedFooter|null
     */
    protected ?array $footer = null;

    /**
     * @var EmbedImage|null
     */
    protected ?array $image = null;

    /**
     * @var EmbedThumbnail|null
     */
    protected ?array $thumbnail = null;

    /**
     * @var EmbedAuthor|null
     */
    protected ?array $author = null;

    /**
     * @var EmbedFields
     */
    protected array $fields = [];

    protected ?int $flags = null;

    public static function make(): self
    {
        return new self;
    }

    public function title(Stringable|string $title): static
    {
        $this->title = (string) $title;

        return $this;
    }

    public function description(Stringable|string $description): static
    {
        $this->description = (string) $description;

        return $this;
    }

    public function url(Stringable|string $url): static
    {
        $this->url = (string) $url;

        return $this;
    }

    /**
     * @param  int<0, 16777215>|HasColor  $color
     */
    public function color(int|HasColor $color): static
    {
        $this->color = $color instanceof HasColor
            ? $color->toColorInt()
            : $color;

        return $this;
    }

    /**
     * @param  DateTimeInterface|string  $timestamp  Timestamp in ISO8601 Format
     */
    public function timestamp(DateTimeInterface|string $timestamp): static
    {
        $this->timestamp = $timestamp instanceof DateTimeInterface
            ? $timestamp->format(DateTimeInterface::ATOM)
            : $timestamp;

        return $this;
    }

    public function footer(Stringable|string $text, Stringable|string|null $iconUrl = null): static
    {
        $text = (string) $text;

        $this->footer = array_filter([
            'text' => $text,
            'icon_url' => $iconUrl !== null ? (string) $iconUrl : null,
        ],
            static fn (mixed $v): bool => $v !== null
        );

        return $this;
    }

    public function image(Stringable|string $url): static
    {
        $this->image = [
            'url' => (string) $url,
        ];

        return $this;
    }

    public function thumbnail(Stringable|string $url): static
    {
        $this->thumbnail = [
            'url' => (string) $url,
        ];

        return $this;
    }

    public function author(
        Stringable|string $name,
        Stringable|string|null $url = null,
        Stringable|string|null $iconUrl = null
    ): static {
        $name = (string) $name;

        $this->author = array_filter([
            'name' => $name,
            'url' => $url !== null ? (string) $url : null,
            'icon_url' => $iconUrl !== null ? (string) $iconUrl : null,
        ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this;
    }

    public function field(
        Stringable|string $name,
        Stringable|string $value,
        bool $inline = false
    ): static {
        $name = (string) $name;
        $value = (string) $value;

        $this->fields[] = array_filter([
            'name' => $name,
            'value' => $value,
            'inline' => $inline ?: null,
        ],
            static fn (mixed $v): bool => $v !== null,
        );

        return $this;
    }

    public function flag(EmbedFlag|int ...$flags): static
    {
        foreach ($flags as $flag) {
            $this->flags = ($this->flags ?? 0) | ($flag instanceof EmbedFlag ? $flag->value : $flag);
        }

        return $this;
    }

    /**
     * @return array{
     *     title?: string,
     *     type: 'rich',
     *     description?: string,
     *     url?: string,
     *     timestamp?: string,
     *     color?: EmbedColor,
     *     footer?: EmbedFooter,
     *     image?: EmbedImage,
     *     thumbnail?: EmbedImage,
     *     author?: EmbedAuthor,
     *     fields?: EmbedFields,
     *     flags?: int
     * }
     */
    public function jsonSerialize(): array
    {
        return array_filter([
            'title' => $this->title,
            'type' => EmbedType::Rich->value,
            'description' => $this->description,
            'url' => $this->url,
            'timestamp' => $this->timestamp,
            'color' => $this->color,
            'footer' => $this->footer,
            'image' => $this->image,
            'thumbnail' => $this->thumbnail,
            'author' => $this->author,
            'fields' => $this->fields ?: null,
            'flags' => $this->flags,
        ], static fn (mixed $v): bool => $v !== null);
    }

    protected function getBitmaskEnumClass(): string
    {
        return EmbedFlag::class;
    }
}
