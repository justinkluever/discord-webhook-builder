<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Enums\Embed;

use JustinKluever\DiscordWebhookBuilder\Concerns\Enums\HasEnumBitmask;
use JustinKluever\DiscordWebhookBuilder\Contracts\Enums\BitmaskBackedEnum;

/**
 * @see https://docs.discord.com/developers/resources/message#embed-object-embed-flags
 */
enum EmbedFlag: int implements BitmaskBackedEnum
{
    use HasEnumBitmask;

    case IS_CONTENT_INVENTORY_ENTRY = 1 << 5;
}
