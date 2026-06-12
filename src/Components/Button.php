<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Components;

use JustinKluever\DiscordWebhookBuilder\Concerns\Components\HasComponentId;
use JustinKluever\DiscordWebhookBuilder\Concerns\Components\HasCustomId;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Component;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Style\IsInteractiveComponent;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Usage\IsMessageComponent;
use JustinKluever\DiscordWebhookBuilder\Enums\Components\ButtonStyle;
use JustinKluever\DiscordWebhookBuilder\Enums\Components\ComponentType;
use JustinKluever\DiscordWebhookBuilder\Support\Components\DiscordEmoji;
use Stringable;

/**
 * Only Link style buttons are allowed with simple webhooks
 *
 * @see https://docs.discord.com/developers/components/reference#button Button Component Documentation
 */
class Button implements Component, IsInteractiveComponent, IsMessageComponent
{
    use HasComponentId;
    use HasCustomId;

    protected ?ButtonStyle $style = null;

    protected ?string $label = null;

    protected ?DiscordEmoji $emoji = null;

    protected ?string $sku = null;

    protected ?string $url = null;

    protected ?bool $disabled = null;

    public static function make(): self
    {
        return new self;
    }

    public function style(ButtonStyle $style): static
    {
        $this->style = $style;

        return $this;
    }

    public function getStyle(): ?ButtonStyle
    {
        return $this->style;
    }

    public function label(Stringable|string $label): static
    {
        $this->label = (string) $label;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function emoji(?DiscordEmoji $emoji): static
    {
        $this->emoji = $emoji;

        return $this;
    }

    public function getEmoji(): ?DiscordEmoji
    {
        return $this->emoji;
    }

    public function sku(Stringable|string|int $sku): static
    {
        $this->sku = (string) $sku;

        return $this;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function url(Stringable|string $url): static
    {
        $this->url = (string) $url;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function disabled(bool $disabled = true): static
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function getDisabled(): ?bool
    {
        return $this->disabled;
    }

    public function getType(): ComponentType
    {
        return ComponentType::Button;
    }

    /**
     * @return array{
     *     type: value-of<ComponentType>,
     *     id?: int,
     *     style?: value-of<ButtonStyle>,
     *     label?: string,
     *     emoji?: DiscordEmoji,
     *     custom_id?: string,
     *     sku_id?: string,
     *     url?: string,
     *     disabled?: bool
     * }
     */
    public function jsonSerialize(): array
    {
        return array_filter([
            'type' => $this->getType()->value,
            'id' => $this->componentId,
            'style' => $this->style?->value,
            'label' => $this->label,
            'emoji' => $this->emoji,
            'custom_id' => $this->customId,
            'sku_id' => $this->sku,
            'url' => $this->url,
            'disabled' => $this->disabled,
        ], static fn (mixed $v): bool => $v !== null);
    }
}
