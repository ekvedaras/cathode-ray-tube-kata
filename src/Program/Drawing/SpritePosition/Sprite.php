<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\Program\Drawing\SpritePosition;

final readonly class Sprite
{
    public int $size;

    public function __construct(private string $contents)
    {
        $this->size = strlen($this->contents);
    }
}