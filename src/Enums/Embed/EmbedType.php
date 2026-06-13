<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Enums\Embed;

enum EmbedType: string
{
    case Rich = 'rich';
    case Image = 'image';
    case Video = 'video';
    case GifVideo = 'gifv';
    case Article = 'article';
    case Link = 'link';
    case PollResult = 'poll_result';
}
