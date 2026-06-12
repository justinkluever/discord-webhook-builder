<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Concerns\Support;

trait HasSpoiler
{
    protected ?bool $spoiler = null;

    public function spoiler(bool $spoiler = true): static
    {
        $this->spoiler = $spoiler;

        return $this;
    }

    public function getSpoiler(): bool
    {
        return $this->spoiler ?? false;
    }
}
