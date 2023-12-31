<?php

use EKvedaras\CathodeRayTube\AsciiSequenceAwareBufferedOutput;
use EKvedaras\CathodeRayTube\CommunicationsDevice;
use EKvedaras\CathodeRayTube\CPU\CPU;
use EKvedaras\CathodeRayTube\CRT\ConsoleScreen;
use EKvedaras\CathodeRayTube\CRT\CRT;
use EKvedaras\CathodeRayTube\CRT\Dimensions;
use EKvedaras\CathodeRayTube\Program\Drawing\DrawBasedOnSpritePosition;
use EKvedaras\CathodeRayTube\Program\Drawing\SpritePosition\Sprite;
use EKvedaras\CathodeRayTube\Program\Drawing\SpritePosition\SpritePosition;
use EKvedaras\CathodeRayTube\Program\InstructionSet;

it('can draw a picture on CRT display using sprite and its position', function (string $sourceCode, Dimensions $screenDimensions, string $sprite, string $expectedImage) {
    $output = new AsciiSequenceAwareBufferedOutput($screenDimensions->width);
    $cpu = new CPU();
    $crt = new CRT(
        screenPlate: new ConsoleScreen(
            dimensions: $screenDimensions,
            output: $output,
        ),
    );
    $device = new CommunicationsDevice(
        cpu: $cpu,
        display: $crt,
    );

    $program = new DrawBasedOnSpritePosition(
        spritePositionProgram: new SpritePosition(
            sprite: new Sprite($sprite),
            instructionSet: InstructionSet::load($sourceCode),
        ),
        crt: $crt,
    );

    $device->run($program);

    expect($output->fetch())->toEqual($expectedImage);
})->with([
    'square' => [
        <<<'Assembly'
        noop
        noop
        addx 2
        addx -2
        noop
        noop
        noop
        Assembly,
        new Dimensions(3, 3),
        '###',
        <<<'Image'
        ###
        #.#
        ###
        Image,
    ],
    'demo' => [
        //<editor-fold desc="Demo program" defaultstate="collapsed">
        <<<'Assembly'
        addx 15
        addx -11
        addx 6
        addx -3
        addx 5
        addx -1
        addx -8
        addx 13
        addx 4
        noop
        addx -1
        addx 5
        addx -1
        addx 5
        addx -1
        addx 5
        addx -1
        addx 5
        addx -1
        addx -35
        addx 1
        addx 24
        addx -19
        addx 1
        addx 16
        addx -11
        noop
        noop
        addx 21
        addx -15
        noop
        noop
        addx -3
        addx 9
        addx 1
        addx -3
        addx 8
        addx 1
        addx 5
        noop
        noop
        noop
        noop
        noop
        addx -36
        noop
        addx 1
        addx 7
        noop
        noop
        noop
        addx 2
        addx 6
        noop
        noop
        noop
        noop
        noop
        addx 1
        noop
        noop
        addx 7
        addx 1
        noop
        addx -13
        addx 13
        addx 7
        noop
        addx 1
        addx -33
        noop
        noop
        noop
        addx 2
        noop
        noop
        noop
        addx 8
        noop
        addx -1
        addx 2
        addx 1
        noop
        addx 17
        addx -9
        addx 1
        addx 1
        addx -3
        addx 11
        noop
        noop
        addx 1
        noop
        addx 1
        noop
        noop
        addx -13
        addx -19
        addx 1
        addx 3
        addx 26
        addx -30
        addx 12
        addx -1
        addx 3
        addx 1
        noop
        noop
        noop
        addx -9
        addx 18
        addx 1
        addx 2
        noop
        noop
        addx 9
        noop
        noop
        noop
        addx -1
        addx 2
        addx -37
        addx 1
        addx 3
        noop
        addx 15
        addx -21
        addx 22
        addx -6
        addx 1
        noop
        addx 2
        addx 1
        noop
        addx -10
        noop
        noop
        addx 20
        addx 1
        addx 2
        addx 2
        addx -6
        addx -11
        noop
        noop
        noop
        Assembly,
        //</editor-fold>
        new Dimensions(40, 6),
        '###',
        <<<'Image'
        ##..##..##..##..##..##..##..##..##..##..
        ###...###...###...###...###...###...###.
        ####....####....####....####....####....
        #####.....#####.....#####.....#####.....
        ######......######......######......####
        #######.......#######.......#######.....
        Image,
    ],
    'input' => [
        //<editor-fold desc="Input program" defaultstate="collapsed">
        <<<'Assembly'
        noop
        addx 10
        addx -4
        addx -1
        noop
        noop
        addx 5
        addx -12
        addx 17
        noop
        addx 1
        addx 2
        noop
        addx 3
        addx 2
        noop
        noop
        addx 7
        addx 3
        noop
        addx 2
        noop
        noop
        addx 1
        addx -38
        addx 5
        addx 2
        addx 3
        addx -2
        addx 2
        addx 5
        addx 2
        addx -4
        addx 26
        addx -19
        addx 2
        addx 5
        addx -2
        addx 7
        addx -2
        addx 5
        addx 2
        addx 4
        addx -17
        addx -23
        addx 1
        addx 5
        addx 3
        noop
        addx 2
        addx 24
        addx 4
        addx -23
        noop
        addx 5
        addx -1
        addx 6
        noop
        addx -2
        noop
        noop
        noop
        addx 7
        addx 1
        addx 4
        noop
        noop
        noop
        noop
        addx -37
        addx 5
        addx 2
        addx 1
        noop
        addx 4
        addx -2
        addx -4
        addx 9
        addx 7
        noop
        noop
        addx 2
        addx 3
        addx -2
        noop
        addx -12
        addx 17
        noop
        addx 3
        addx 2
        addx -3
        addx -30
        addx 3
        noop
        addx 2
        addx 3
        addx -2
        addx 2
        addx 5
        addx 2
        addx 11
        addx -6
        noop
        addx 2
        addx -19
        addx 20
        addx -7
        addx 14
        addx 8
        addx -7
        addx 2
        addx -26
        addx -7
        noop
        noop
        addx 5
        addx -2
        addx 5
        addx 15
        addx -13
        addx 5
        noop
        noop
        addx 1
        addx 4
        addx 3
        addx -2
        addx 4
        addx 1
        noop
        addx 2
        noop
        addx 3
        addx 2
        noop
        noop
        noop
        noop
        noop
        Assembly,
        //</editor-fold>
        new Dimensions(40, 6),
        '###',
        <<<'Image'
        ###...##...##..####.#..#.#....#..#.####.
        ##.#.#..#.#..#.#....#.#..#....#..#.#....
        ###..#..#.#....###..##...#....####.###..
        ##.#.####.#....#....#.#..#....#..#.#....
        #..#.#..#.#..#.#....#.#..#....#..#.#....
        ###..#..#..##..####.#..#.####.#..#.#....
        Image,
    ],
]);