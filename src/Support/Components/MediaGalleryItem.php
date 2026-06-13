<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Support\Components;

use JsonSerializable;
use Stringable;

readonly class MediaGalleryItem implements JsonSerializable
{
    public UnfurledMediaItem $media;

    public ?string $description;

    public static function make(
        UnfurledMediaItem|string $media,
        Stringable|string|null $description = null,
        bool $spoiler = false
    ): self {
        return new self($media, $description, $spoiler);
    }

    public function __construct(
        UnfurledMediaItem|string $media,
        Stringable|string|null $description = null,
        public bool $spoiler = false
    ) {
        $this->media = $media instanceof UnfurledMediaItem
            ? $media
            : UnfurledMediaItem::make($media);

        if ($description !== null && trim((string) $description) === '') {
            $description = null;
        }

        $this->description = $description !== null ? (string) $description : null;
    }

    /**
     * @return array{
     *     media: UnfurledMediaItem,
     *     description?: string,
     *     spoiler?: bool
     * }
     */
    public function jsonSerialize(): array
    {
        return array_filter([
            'media' => $this->media,
            'description' => $this->description,
            'spoiler' => $this->spoiler ?: null,
        ], static fn (mixed $v): bool => $v !== null);
    }
}
