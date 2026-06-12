<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Enums\Support;

use JustinKluever\DiscordWebhookBuilder\Concerns\Enums\HasEnumBitmask;
use JustinKluever\DiscordWebhookBuilder\Contracts\Enums\BitmaskBackedEnum;

/**
 * @see https://docs.discord.com/developers/components/reference#unfurled-media-item-unfurled-media-item-flags
 */
enum UnfurledMediaFlag: int implements BitmaskBackedEnum
{
    use HasEnumBitmask;

    case IS_ANIMATED = 1 << 0;
}
