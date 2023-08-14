<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube;

use EKvedaras\CathodeRayTube\CRT\Position;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\Output;

final class AsciiSequenceAwareBufferedOutput extends Output
{
    private string $buffer = '';

    public function __construct(private readonly int $screenWidth, ?int $verbosity = self::VERBOSITY_NORMAL, bool $decorated = false, OutputFormatterInterface $formatter = null)
    {
        parent::__construct($verbosity, $decorated, $formatter);
    }


    public function fetch(): string
    {
        $content = $this->buffer;
        $this->buffer = '';

        return $content;
    }

    protected function doWrite(string $message, bool $newline): void
    {
        $rawMessage = $this->raw($message);

        if ($this->containsPosition($message)) {
            $positionInBuffer = $this->positionInBuffer($message);
            $this->buffer = substr($this->buffer, 0, $positionInBuffer) . $rawMessage . substr($this->buffer, $positionInBuffer + strlen($rawMessage));

            return;
        }

        $this->buffer .= $rawMessage;

        if ($newline) {
            $this->buffer .= \PHP_EOL;
        }
    }

    private function containsPosition(string $message): bool
    {
        return preg_match('/\\e\[(\d+);(\d+)H/', $message) === 1;
    }

    private function positionInBuffer(string $message): int
    {
        $matches = [];
        preg_match('/\\e\[(\d+);(\d+)H/', $message, $matches);

        $position = new Position(x: (int) $matches[1], y: (int) $matches[0]);

        return $position->linear(width: $this->screenWidth);
    }

    private function raw(string $message): string
    {
        return (string) preg_replace('/(\\e\[(.+)([HJ]))(.*)/', '$4', $message);
    }
}