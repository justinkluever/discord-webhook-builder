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
 * @see https://docs.discord.com/developers/components/reference#file File Component Documentation
 */
class File implements Component, IsComponentsV2, IsContentComponent, IsMessageComponent
{
    use HasComponentId;
    use HasSpoiler;

    protected UnfurledMediaItem $file;

    public static function make(UnfurledMediaItem|Stringable|string $file): self
    {
        return new self($file);
    }

    public function __construct(
        UnfurledMediaItem|Stringable|string $file
    ) {
        $this->file($file);
    }

    public function file(UnfurledMediaItem|Stringable|string $media): static
    {
        $this->file = $media instanceof UnfurledMediaItem
            ? $media
            : UnfurledMediaItem::make((string) $media);

        return $this;
    }

    public function getFile(): UnfurledMediaItem
    {
        return $this->file;
    }

    /**
     * @return array{
     *     type: value-of<ComponentType>,
     *     id?: int,
     *     file: UnfurledMediaItem,
     *     spoiler?: bool
     * }
     */
    public function jsonSerialize(): array
    {
        return array_filter([
            'type' => $this->getType()->value,
            'id' => $this->componentId,
            'file' => $this->file,
            'spoiler' => $this->spoiler ?: null,
        ], static fn (mixed $v): bool => $v !== null);
    }

    public function getType(): ComponentType
    {
        return ComponentType::File;
    }
}
