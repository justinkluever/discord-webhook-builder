<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Components;

use JustinKluever\DiscordWebhookBuilder\Concerns\Components\HasComponentId;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Component;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\IsComponentsV2;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Style\IsLayoutComponent;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Usage\IsMessageComponent;
use JustinKluever\DiscordWebhookBuilder\Enums\Components\ComponentType;
use RuntimeException;

/**
 * @see https://docs.discord.com/developers/components/reference#section Section Component Documentation
 */
class Section implements Component, IsComponentsV2, IsLayoutComponent, IsMessageComponent
{
    use HasComponentId;

    /**
     * @var Component[]
     */
    protected array $components = [];

    protected ?Component $accessory = null;

    public static function make(?Component $accessory = null, Component ...$components): self
    {
        return new self($accessory, ...$components);
    }

    public function __construct(
        ?Component $accessory,
        Component ...$components
    ) {
        $this->accessory($accessory);
        $this->components(...$components);
    }

    /**
     * @return Component[]
     */
    public function getComponents(): array
    {
        return $this->components;
    }

    public function accessory(?Component $component): static
    {
        $this->accessory = $component;

        return $this;
    }

    public function getAccessory(): ?Component
    {
        return $this->accessory;
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
     *     type: positive-int,
     *     id?: int,
     *     components: array<Component>,
     *     accessory: Component
     * }
     */
    public function jsonSerialize(): array
    {
        if (is_null($this->accessory)) {
            throw new RuntimeException('Section requires an accessory component.');
        }

        return array_filter([
            'type' => $this->getType()->value,
            'id' => $this->componentId,
            'components' => $this->components,
            'accessory' => $this->accessory,
        ], static fn (mixed $v): bool => $v !== null);
    }

    public function getType(): ComponentType
    {
        return ComponentType::Section;
    }
}
