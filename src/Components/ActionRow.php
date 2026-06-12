<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Components;

use JustinKluever\DiscordWebhookBuilder\Concerns\Components\HasComponentId;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Component;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Style\IsLayoutComponent;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Usage\IsMessageComponent;
use JustinKluever\DiscordWebhookBuilder\Enums\Components\ComponentType;

/**
 * @see https://docs.discord.com/developers/components/reference#action-row Action Row Component Documentation
 */
class ActionRow implements Component, IsLayoutComponent, IsMessageComponent
{
    use HasComponentId;

    /**
     * @var Component[]
     */
    protected array $components = [];

    public static function make(Component ...$components): self
    {
        return new self(...$components);
    }

    public function __construct(
        Component ...$components
    ) {
        $this->components(...$components);
    }

    /**
     * @return Component[]
     */
    public function getComponents(): array
    {
        return $this->components;
    }

    public function components(Component ...$components): static
    {
        foreach ($components as $component) {
            $this->components[] = $component;
        }

        return $this;
    }

    /**
     * @return array{
     *     type: value-of<ComponentType>,
     *     id?: int,
     *     components: array<Component>
     * }
     */
    public function jsonSerialize(): array
    {
        return array_filter([
            'type' => $this->getType()->value,
            'id' => $this->componentId,
            'components' => $this->components,
        ], static fn (mixed $v): bool => $v !== null);
    }

    public function getType(): ComponentType
    {
        return ComponentType::ActionRow;
    }
}
