<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Components;

use JustinKluever\DiscordWebhookBuilder\Concerns\Components\HasComponentId;
use JustinKluever\DiscordWebhookBuilder\Concerns\Support\HasSpoiler;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Component;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\IsComponentsV2;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Style\IsContentComponent;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Usage\IsMessageComponent;
use JustinKluever\DiscordWebhookBuilder\Enums\Components\ComponentType;
use JustinKluever\DiscordWebhookBuilder\Support\Components\UnfurledMediaItem;
use Stringable;

/**
 * @see https://docs.discord.com/developers/components/reference#thumbnail Thumbnail Component Documentation
 */
class Thumbnail implements Component, IsComponentsV2, IsContentComponent, IsMessageComponent
{
    use HasComponentId;
    use HasSpoiler;

    protected UnfurledMediaItem $media;

    protected ?string $description = null;

    public static function make(UnfurledMediaItem|Stringable|string $media): self
    {
        return new self($media);
    }

    public function __construct(
        UnfurledMediaItem|Stringable|string $mediaUrl
    ) {
        $this->media($mediaUrl);
    }

    public function media(UnfurledMediaItem|Stringable|string $media): static
    {
        $this->media = $media instanceof UnfurledMediaItem
            ? $media
            : UnfurledMediaItem::make((string) $media);

        return $this;
    }

    public function getMedia(): UnfurledMediaItem
    {
        return $this->media;
    }

    public function description(Stringable|string|null $description): static
    {
        if ($description instanceof Stringable) {
            $description = (string) $description;
        }

        if ($description !== null && trim($description) === '') {
            $description = null;
        }

        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return array{
     *     type: value-of<ComponentType>,
     *     id?: int,
     *     media: UnfurledMediaItem,
     *     description?: string,
     *     spoiler?: bool
     * }
     */
    public function jsonSerialize(): array
    {
        return array_filter([
            'type' => $this->getType()->value,
            'id' => $this->componentId,
            'media' => $this->media,
            'description' => $this->description,
            'spoiler' => $this->spoiler ?: null,
        ], static fn (mixed $v): bool => $v !== null);
    }

    public function getType(): ComponentType
    {
        return ComponentType::Thumbnail;
    }
}
