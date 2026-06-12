<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Contracts\Enums;

use BackedEnum;

interface BitmaskBackedEnum
{
    /**
     * Check if this specific flag is present within a given bitmask integer.
     */
    public function isIn(int $bitmask): bool;

    /**
     * Check if a bitmask contains a specific flag instance or raw integer value.
     */
    public static function has(?int $bitmask, BackedEnum|int $flag): bool;

    /**
     * Combine multiple flags or values into a single bitmask integer.
     */
    public static function bitmask(BackedEnum|int ...$flags): int;

    /**
     * Convert a raw bitmask integer into an array of matching Enum instances.
     *
     * @return array<int, static>
     */
    public static function fromBitmask(int $bitmask): array;
}
