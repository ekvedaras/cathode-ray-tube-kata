<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\Program;

use EKvedaras\CathodeRayTube\Program\Instructions\AddX;
use EKvedaras\CathodeRayTube\Program\Instructions\Instruction;
use EKvedaras\CathodeRayTube\Program\Instructions\Noop;
use EKvedaras\CathodeRayTube\CPU\CPU;
use InvalidArgumentException;

final readonly class InstructionSet implements Program
{
    /** @var list<Instruction> */
    private array $instructions;

    public function __construct(Instruction ...$instructions)
    {
        $this->instructions = $instructions;
    }

    public function run(CPU $on): void
    {
        foreach ($this->instructions as $instruction) {
            $instruction->run($on);
        }

        $on->tickUntilBufferIsEmpty();
    }

    public static function load(string $sourceCode): self
    {
        $lines = explode("\n", trim($sourceCode));

        return new self(...array_values(array_map(
            function (string $line, int $lineNumber) {
                $params = explode(' ', trim($line));
                $instruction = array_shift($params);

                return match ($instruction) {
                    'noop' => new Noop(),
                    'addx' => new AddX(
                        value: (int) array_shift($params),
                    ),
                    default => throw new InvalidArgumentException("Invalid instruction [$instruction] at line [$lineNumber]: $line"),
                };
            },
            $lines,
            array_map(fn (int $lineIndex) => $lineIndex + 1, array_keys($lines)),
        )));
    }
}