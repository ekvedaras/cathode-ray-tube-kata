<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube;

use Symfony\Component\Console\Output\Output;

final class AsciiSequenceAwareBufferedOutput extends Output
{
    private string $buffer = '';

    public function fetch(): string
    {
        $content = $this->buffer;
        $this->buffer = '';

        return $content;
    }

    protected function doWrite(string $message, bool $newline): void
    {
        if ($this->containsPosition($message)) {
            $this->buffer[$this->position($message)] = $this->withoutPosition($message);

            return;
        }

        $this->buffer .= $message;

        if ($newline) {
            $this->buffer .= \PHP_EOL;
        }
    }
}