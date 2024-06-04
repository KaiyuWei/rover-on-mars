<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\InputInstructionParser;

class InputInstructionParserTest extends TestCase
{
    public function test_parser_function()
    {
        $input = "5 5
        1 2 N
        LMLMLMLMM
        3 3 E
        MMRMMRMRRM";

        $expectedOutput = [
            'plateau' => ['sizeX' => 5, 'sizeY' => 5],
            'robots' => [
                [
                    'initial' => ['xCoordinate' => 1, 'yCoordinate' => 2, 'currentDirection' => 'N'],
                    'movement' => ['L', 'M', 'L', 'M', 'L', 'M', 'L', 'M', 'M']
                ],
                [
                    'initial' => ['xCoordinate' => 3, 'yCoordinate' => 3, 'currentDirection' => 'E'],
                    'movement' => ['M', 'M', 'R', 'M', 'M', 'R', 'M', 'R', 'R', 'M']
                ]
            ]
        ];

        $parsedData = InputInstructionParser::parseMarsRoverInput($input);

        $this->assertEquals($expectedOutput, $parsedData);
    }
}
