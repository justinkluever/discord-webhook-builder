<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Concerns\Enums;

use BackedEnum;

trait HasEnumBitmask
{
    public function isIn(int $bitmask): bool
    {
        return ($bitmask & $this->value) === $this->value;
    }

    public static function has(?int $bitmask, BackedEnum|int $flag): bool
    {
        if ($bitmask === null) {
            return false;
        }

        if ($flag instanceof self) {
            return $flag->isIn($bitmask);
        }

        if ($flag instanceof BackedEnum) {
            return false;
        }

        return $flag > 0 && ($bitmask & $flag) === $flag;
    }

    public static function bitmask(BackedEnum|int ...$flags): int
    {
        $mask = 0;

        foreach ($flags as $flag) {
            if ($flag instanceof self) {
                $mask |= $flag->value;
            } elseif (is_int($flag) && $flag > 0) {
                $mask |= $flag;
            }
        }

        return $mask;
    }

    /**
     * @return array<int, static>
     */
    public static function fromBitmask(int $bitmask): array
    {
        if ($bitmask <= 0) {
            return [];
        }

        $flags = [];
        foreach (self::cases() as $case) {
            if ($case->isIn($bitmask)) {
                $flags[] = $case;
            }
        }

        return $flags;
    }
}
