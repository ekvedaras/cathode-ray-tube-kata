<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\CRT;

interface Screen
{
    public function dimensions(): Dimensions;

    public function lightPixel(Position $at): void;

    public function darkPixel(Position $at): void;
}