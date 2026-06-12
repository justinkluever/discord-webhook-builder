<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Concerns\Components;

use InvalidArgumentException;

trait HasComponentId
{
    protected ?int $componentId = null;

    public function getComponentId(): ?int
    {
        return $this->componentId;
    }

    /**
     * @see https://docs.discord.com/developers/components/reference#anatomy-of-a-component
     *
     * @param  positive-int|null  $id  32-bit integer used as an optional identifier for component
     */
    public function componentId(?int $id): static
    {
        if ($id !== null) {
            if ($id < 0) {
                throw new InvalidArgumentException(static::class.': Component Id must be a positive integer.');
            }

            if ($id > (2 ** 31 - 1)) {
                throw new InvalidArgumentException(static::class.': Component Id must not be higher than 2^31-1 (2147483647).');
            }
        }

        $this->componentId = $id;

        return $this;
    }
}
