<?php

declare(strict_types=1);

it('checks that true is not false', function (): void {
    expect(true)->not->toBeFalse();
});
