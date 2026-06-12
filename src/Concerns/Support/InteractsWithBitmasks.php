<?php

namespace JustinKluever\DiscordWebhookBuilder\Concerns\Support;

use BackedEnum;
use JustinKluever\DiscordWebhookBuilder\Contracts\Enums\BitmaskBackedEnum;

trait InteractsWithBitmasks
{
    /**
     * @return class-string<BitmaskBackedEnum>
     */
    abstract protected function getBitmaskEnumClass(): string;

    protected function getBitmaskPropertyName(): string
    {
        return 'flags';
    }

    public function hasFlag(BackedEnum|int $flag): bool
    {
        /** @var class-string<BitmaskBackedEnum> $enumClass */
        $enumClass = $this->getBitmaskEnumClass();

        /** @var int|null $bitmaskValue */
        $bitmaskValue = $this->{$this->getBitmaskPropertyName()};

        return $enumClass::has($bitmaskValue, $flag);
    }
}
