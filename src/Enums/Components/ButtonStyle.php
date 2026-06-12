<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Enums\Components;

/**
 * @see https://docs.discord.com/developers/components/reference#what-is-a-component
 */
enum ButtonStyle: int
{
    case Primary = 1;
    case Secondary = 2;
    case Success = 3;
    case Danger = 4;
    case Link = 5;
    case Premium = 6;
}
