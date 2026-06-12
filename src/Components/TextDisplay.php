<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Components;

use JustinKluever\DiscordWebhookBuilder\Concerns\Components\HasComponentId;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Component;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\IsComponentsV2;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Style\IsContentComponent;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Usage\IsMessageComponent;
use JustinKluever\DiscordWebhookBuilder\Contracts\Components\Usage\IsModalComponent;
use JustinKluever\DiscordWebhookBuilder\Enums\Components\ComponentType;
use Stringable;

/**
 * @see https://docs.discord.com/developers/components/reference#text-display Text Display Component Documentation
 */
class TextDisplay implements Component, IsComponentsV2, IsContentComponent, IsMessageComponent, IsModalComponent
{
    use HasComponentId;

    protected string $content = '';

    public static function make(Stringable|string $content = ''): self
    {
        return new self($content);
    }

    public function __construct(Stringable|string $content = '')
    {
        $this->content($content);
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function content(Stringable|string $content): static
    {
        $this->content = (string) $content;

        return $this;
    }

    /**
     * @return array{
     *     type: value-of<ComponentType>,
     *     id?: int,
     *     content: string
     * }
     */
    public function jsonSerialize(): array
    {
        return array_filter([
            'type' => $this->getType()->value,
            'id' => $this->componentId,
            'content' => $this->content,
        ], static fn (mixed $v): bool => $v !== null);
    }

    public function getType(): ComponentType
    {
        return ComponentType::TextDisplay;
    }
}
