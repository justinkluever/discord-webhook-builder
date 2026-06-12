<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Support\Components;

use JsonSerializable;
use JustinKluever\DiscordWebhookBuilder\Concerns\Support\InteractsWithBitmasks;
use JustinKluever\DiscordWebhookBuilder\Enums\Support\UnfurledMediaFlag;
use Stringable;

/**
 * Only Url can be set from us,
 * everything else is just for convenience if you want to have a full object from a response containing media items.
 *
 * @see https://docs.discord.com/developers/components/reference#unfurled-media-item Unfurled Media Item Documentation
 *
 * @phpstan-type UnfurledMediaItemShape array{
 *       url: string,
 *       proxy_url: string|null,
 *       height: int|null,
 *       width: int|null,
 *       placeholder: string|null,
 *       placeholder_version: int|null,
 *       content_type: string|null,
 *       flags: int|null,
 *       attachment_id: string|null
 *   }
 * @phpstan-type MinimalUnfurledMediaItemShape array{
 *       url: string,
 *   }
 */
readonly class UnfurledMediaItem implements JsonSerializable
{
    use InteractsWithBitmasks;

    public static function make(Stringable|string $url): self
    {
        return new self((string) $url);
    }

    public static function attachment(Stringable|string $filename): self
    {
        return new self('attachment://'.$filename);
    }

    /**
     * Hydrate from a Discord API response payload.
     *
     * @param  UnfurledMediaItemShape  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            url: $data['url'],
            proxyUrl: $data['proxy_url'] ?? null,
            height: $data['height'] ?? null,
            width: $data['width'] ?? null,
            placeholder: $data['placeholder'] ?? null,
            placeholderVersion: $data['placeholder_version'] ?? null,
            contentType: $data['content_type'] ?? null,
            flags: $data['flags'] ?? null,
            attachmentId: $data['attachment_id'] ?? null
        );
    }

    public function __construct(
        protected string $url,
        protected ?string $proxyUrl = null,
        protected ?int $height = null,
        protected ?int $width = null,
        protected ?string $placeholder = null,
        protected ?int $placeholderVersion = null,
        protected ?string $contentType = null,
        protected ?int $flags = null,
        protected ?string $attachmentId = null,
    ) {}

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getProxyUrl(): ?string
    {
        return $this->proxyUrl;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }

    public function getPlaceholderVersion(): ?int
    {
        return $this->placeholderVersion;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function getFlags(): ?int
    {
        return $this->flags;
    }

    public function isAnimated(): bool
    {
        return $this->hasFlag(UnfurledMediaFlag::IS_ANIMATED);
    }

    public function getAttachmentId(): ?string
    {
        return $this->attachmentId;
    }

    public function isAttachment(): bool
    {
        return str_starts_with($this->url, 'attachment://');
    }

    /**
     * @return UnfurledMediaItemShape
     */
    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'proxy_url' => $this->proxyUrl,
            'height' => $this->height,
            'width' => $this->width,
            'placeholder' => $this->placeholder,
            'placeholder_version' => $this->placeholderVersion,
            'content_type' => $this->contentType,
            'flags' => $this->flags,
            'attachment_id' => $this->attachmentId,
        ];
    }

    /**
     * @return MinimalUnfurledMediaItemShape
     */
    public function jsonSerialize(): array
    {
        return [
            'url' => $this->url,
        ];
    }

    protected function getBitmaskEnumClass(): string
    {
        return UnfurledMediaFlag::class;
    }
}
