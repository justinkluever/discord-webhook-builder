<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Components;

use JustinKluever\DiscordWebhookBuilder\Concerns\Components\HasComponentId;
use JustinKluever\DiscordWebhookBuilder\Concerns\Support\HasSpoiler;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Component;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\IsComponentsV2;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Style\IsLayoutComponent;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Usage\IsMessageComponent;
use JustinKluever\DiscordWebhookBuilder\Contracts\Support\HasColor;
use JustinKluever\DiscordWebhookBuilder\Enums\Components\ComponentType;

/**
 * @see https://docs.discord.com/developers/components/reference#container Container Component Documentation
 */
class Container implements Component, IsComponentsV2, IsLayoutComponent, IsMessageComponent
{
    use HasComponentId;
    use HasSpoiler;

    /**
     * @var Component[]
     */
    protected array $components = [];

    /**
     * @var int<0, 16777215>|null
     */
    protected ?int $accent_color = null;

    public static function make(Component ...$components): self
    {
        return new self(...$components);
    }

    public function __construct(Component ...$components)
    {
        $this->components(...$components);
    }

    public function components(Component ...$components): static
    {
        foreach ($components as $component) {
            $this->components[] = $component;
        }

        return $this;
    }

    /**
     * @return Component[]
     */
    public function getComponents(): array
    {
        return $this->components;
    }

    /**
     * @param  int<0, 16777215>|HasColor|null  $accentColor
     * @return $this
     */
    public function accentColor(int|HasColor|null $accentColor): static
    {
        $this->accent_color = $accentColor instanceof HasColor
            ? $accentColor->toColorInt()
            : $accentColor;

        return $this;
    }

    /**
     * @return int<0, 16777215>|null
     */
    public function getAccentColor(): ?int
    {
        return $this->accent_color;
    }

    /**
     * @return array{
     *     type: value-of<ComponentType>,
     *     id?: int,
     *     components: array<Component>,
     *     accent_color?: int<0, 16777215>,
     *     spoiler?: bool
     * }
     */
    public function jsonSerialize(): array
    {
        return array_filter([
            'type' => $this->getType()->value,
            'id' => $this->componentId,
            'components' => $this->components,
            'accent_color' => $this->accent_color,
            'spoiler' => $this->spoiler,
        ], static fn (mixed $v): bool => $v !== null);
    }

    public function getType(): ComponentType
    {
        return ComponentType::Container;
    }
}
