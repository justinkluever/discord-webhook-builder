<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Contracts\Support;

interface HasColor
{
    /**
     * @return int<0, 16777215>
     */
    public function toColorInt(): int;
}
