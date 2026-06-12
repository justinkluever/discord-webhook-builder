<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Concerns\Components;

use InvalidArgumentException;
use Stringable;

trait HasCustomId
{
    protected ?string $customId = null;

    public function getCustomId(): ?string
    {
        return $this->customId;
    }

    public function customId(Stringable|string $customId): static
    {
        $customId = (string) $customId;

        $length = mb_strlen($customId);

        if ($length < 1) {
            throw new InvalidArgumentException(static::class.': Custom Id must be at least 1 Character, 0 provided.');
        }

        if ($length > 100) {
            throw new InvalidArgumentException(static::class.sprintf(': Custom Id must be at most 100 Characters, %d provided.', $length));
        }

        $this->customId = $customId;

        return $this;
    }
}
