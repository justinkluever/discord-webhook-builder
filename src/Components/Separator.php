<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Components;

use JustinKluever\DiscordWebhookBuilder\Concerns\Components\HasComponentId;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Component;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\IsComponentsV2;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Style\IsLayoutComponent;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Usage\IsMessageComponent;
use JustinKluever\DiscordWebhookBuilder\Enums\Components\ComponentType;

/**
 * @see https://docs.discord.com/developers/components/reference#separator Separator Component Documentation
 */
class Separator implements Component, IsComponentsV2, IsLayoutComponent, IsMessageComponent
{
    use HasComponentId;

    public static function make(?bool $divider = null, ?int $spacing = null): self
    {
        return new self($divider, $spacing);
    }

    public function __construct(
        protected ?bool $divider = null,
        protected ?int $spacing = null
    ) {
        //
    }

    public function divider(?bool $divider = true): static
    {
        $this->divider = $divider;

        return $this;
    }

    public function getDivider(): bool
    {
        return $this->divider ?? false;
    }

    public function spacing(?int $spacing = 1): static
    {
        $this->spacing = $spacing;

        return $this;
    }

    public function getSpacing(): ?int
    {
        return $this->spacing;
    }

    /**
     * @return array{
     *     type: value-of<ComponentType>,
     *     id?: int,
     *     divider?: bool,
     *     spacing?: int,
     * }
     */
    public function jsonSerialize(): array
    {
        return array_filter([
            'type' => $this->getType()->value,
            'id' => $this->componentId,
            'divider' => $this->divider,
            'spacing' => $this->spacing,
        ], static fn (mixed $v): bool => $v !== null);
    }

    public function getType(): ComponentType
    {
        return ComponentType::Separator;
    }
}
