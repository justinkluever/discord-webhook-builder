<?php

declare(strict_types=1);

namespace JustinKluever\DiscordWebhookBuilder\Support;

use InvalidArgumentException;
use JustinKluever\DiscordWebhookBuilder\Contracts\Support\HasColor;

readonly class Color implements HasColor
{
    private function __construct(
        /** @var int<0, 16777215> */
        public int $value,
    ) {}

    public static function fromInt(int $value): self
    {
        if ($value < 0 || $value > 0xFFFFFF) {
            throw new InvalidArgumentException('Color must be between 0 and 16777215 (0xFFFFFF).');
        }

        return new self($value);
    }

    public static function fromHex(string $hex): self
    {
        $hex = ltrim($hex, '#');

        if (! preg_match('/^[0-9a-fA-F]{6}$/', $hex)) {
            throw new InvalidArgumentException(sprintf("Invalid hex color: #%s. Keep in mind that we don't support Alpha here.", $hex));
        }

        return self::fromInt((int) hexdec($hex));
    }

    public static function fromRgb(int $r, int $g, int $b): self
    {
        foreach (['r' => $r, 'g' => $g, 'b' => $b] as $channel => $value) {
            if ($value < 0 || $value > 255) {
                throw new InvalidArgumentException(sprintf("Channel '%s' must be between 0 and 255.", $channel));
            }
        }

        return self::fromInt(($r << 16) | ($g << 8) | $b);
    }

    public function toColorInt(): int
    {
        return $this->value;
    }
}
