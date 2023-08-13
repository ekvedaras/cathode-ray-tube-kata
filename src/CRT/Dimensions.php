<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\CRT;

use Iterator;

final class Dimensions implements Iterator
{
    private ?Position $position = null;

    public function __construct(
        public readonly int $width,
        public readonly int $height,
    ) {
    }

    public function current(): Position
    {
        $this->position ??= new Position(0, 0);

        return $this->position;
    }

    public function next(): void
    {
        $this->position = new Position($this->current()->x + 1, $this->current()->y);

        if ($this->position->x > $this->width) {
            $this->position = new Position(0, $this->position->y + 1);
        }

        if ($this->position->y > $this->height) {
            $this->position = null;
        }
    }

    public function key(): ?Position
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return $this->position !== null;
    }

    public function rewind(): void
    {
        $this->position = new Position(0, 0);
    }
}