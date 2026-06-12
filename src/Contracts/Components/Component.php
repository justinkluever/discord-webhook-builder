<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Contracts\Components;

use JsonSerializable;
use JustinKluever\DiscordWebhookBuilder\Enums\Components\ComponentType;

interface Component extends JsonSerializable
{
    public function getType(): ComponentType;

    public function getComponentId(): ?int;
}
