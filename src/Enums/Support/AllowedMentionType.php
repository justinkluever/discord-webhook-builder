<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Enums\Support;

enum AllowedMentionType: string
{
    case Roles = 'roles';
    case Users = 'users';
    case Everyone = 'everyone';
}
