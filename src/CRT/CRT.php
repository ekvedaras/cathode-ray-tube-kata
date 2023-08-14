<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\CRT;

final class CRT
{
    private int $cycle = 0;
    private bool $active = false;

    public function __construct(
        public readonly Screen $screenPlate,
    ) {
    }

    public function activateForOneCycle(): void
    {
        $this->active = true;
    }

    public function tick(): void
    {
        if ($this->active) {
            $this->screenPlate->lightPixel(at: $this->position());
        } else {
            $this->screenPlate->darkPixel(at: $this->position());
        }

        $this->cycle++;
        if ($this->row() >= $this->screenPlate->dimensions()->height) {
            $this->cycle = 0;
        }

        $this->active = false;
    }

    private function position(): Position
    {
        return new Position(
            x: $this->column(),
            y: $this->row(),
        );
    }

    public function column(): int
    {
        if ($this->cycle < $this->screenPlate->dimensions()->width) {
            return $this->cycle;
        }

        return $this->cycle % $this->screenPlate->dimensions()->width;
    }

    private function row(): int
    {
        return (int) floor($this->cycle / $this->screenPlate->dimensions()->width);
    }
}