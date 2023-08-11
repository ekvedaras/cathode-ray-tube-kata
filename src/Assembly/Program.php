<?php

declare(strict_types=1);

namespace EKvedaras\CathodeRayTube\Assembly;

use EKvedaras\CathodeRayTube\Assembly\Instructions\Add;
use EKvedaras\CathodeRayTube\Assembly\Instructions\Instruction;
use EKvedaras\CathodeRayTube\Assembly\Instructions\Noop;
use EKvedaras\CathodeRayTube\CPU;
use EKvedaras\CathodeRayTube\RegisterKey;
use InvalidArgumentException;

final readonly class Program
{
    /** @var list<Instruction> */
    private array $instructions;

    public function __construct(Instruction ...$instructions)
    {
        $this->instructions = $instructions;
    }

    public function run(CPU $cpu): void
    {
        foreach ($this->instructions as $instruction) {
            $instruction->run($cpu);
        }
    }

    public static function load(string $sourceCode): self
    {
        $lines = explode("\n", trim($sourceCode));

        return new self(...array_values(array_map(
            function (string $line, int $lineNumber) {
                [$instruction, $argument] = explode(' ', trim($line));

                return match (substr($instruction, 0, 3)) {
                    'noo' => new Noop(),
                    'add' => new Add(
                        register: RegisterKey::from(substr($instruction, 3)),
                        value: (int) $argument,
                    ),
                    default => throw new InvalidArgumentException("Invalid instruction [$instruction] at line [$lineNumber]: $line"),
                };
            },
            $lines,
            array_map(fn (int $lineIndex) => $lineIndex + 1, array_keys($lines)),
        )));
    }
}