<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\CRT;

use Symfony\Component\Console\Output\OutputInterface;

final readonly class ConsoleScreen implements Screen
{
    private const darkPixel = '.';
    private const litPixel = '#';

    public function __construct(
        private Dimensions $dimensions,
        private OutputInterface $output,
    ) {
        $this->fill();
    }

    private function fill(): void
    {
        $lastY = 0;

        foreach ($this->dimensions as $position) {
            if ($position->y > $lastY) {
                $this->output->write("\n");
                $lastY = $position->y;
            }

            $this->output->write(self::darkPixel);
        }
    }

    public function dimensions(): Dimensions
    {
        return $this->dimensions;
    }

    public function lightPixel(Position $at): void
    {
        $this->output->write($at->asAsciiSequence() . self::litPixel);
    }

    public function darkPixel(Position $at): void
    {
        $this->output->write($at->asAsciiSequence() . self::darkPixel);
    }
}