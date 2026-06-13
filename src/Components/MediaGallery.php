<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Components;

use JustinKluever\DiscordWebhookBuilder\Concerns\Components\HasComponentId;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Component;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\IsComponentsV2;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Style\IsContentComponent;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Usage\IsMessageComponent;
use JustinKluever\DiscordWebhookBuilder\Enums\Components\ComponentType;
use JustinKluever\DiscordWebhookBuilder\Support\Components\MediaGalleryItem;
use JustinKluever\DiscordWebhookBuilder\Support\Components\UnfurledMediaItem;

/**
 * @see https://docs.discord.com/developers/components/reference#media-gallery Media Gallery Component Documentation
 */
class MediaGallery implements Component, IsComponentsV2, IsContentComponent, IsMessageComponent
{
    use HasComponentId;

    /**
     * @var MediaGalleryItem[]
     */
    protected array $items = [];

    public static function make(MediaGalleryItem|UnfurledMediaItem|string ...$items): self
    {
        return new self(...$items);
    }

    public function items(MediaGalleryItem|UnfurledMediaItem|string ...$items): static
    {
        foreach ($items as $item) {
            $this->items[] = $item instanceof MediaGalleryItem
                ? $item
                : new MediaGalleryItem($item);
        }

        return $this;
    }

    /**
     * @return MediaGalleryItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function __construct(
        MediaGalleryItem|UnfurledMediaItem|string ...$items
    ) {
        $this->items(...$items);
    }

    /**
     * @return array{
     *     type: value-of<ComponentType>,
     *     id?: int,
     *     items: array<MediaGalleryItem>
     * }
     */
    public function jsonSerialize(): array
    {
        return array_filter([
            'type' => $this->getType()->value,
            'id' => $this->componentId,
            'items' => $this->items,
        ], static fn (mixed $v): bool => $v !== null);
    }

    public function getType(): ComponentType
    {
        return ComponentType::MediaGallery;
    }
}
