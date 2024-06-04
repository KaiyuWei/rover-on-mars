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
            'plateau' => ['x' => 5, 'y' => 5],
            'robots' => [
                [
                    'initial position' => ['x' => 1, 'y' => 2, 'direction' => 'N'],
                    'movement' => ['L', 'M', 'L', 'M', 'L', 'M', 'L', 'M', 'M']
                ],
                [
                    'initial position' => ['x' => 3, 'y' => 3, 'direction' => 'E'],
                    'movement' => ['M', 'M', 'R', 'M', 'M', 'R', 'M', 'R', 'R', 'M']
                ]
            ]
        ];

        $parsedData = InputInstructionParser::parseMarsRoverInput($input);

        $this->assertEquals($expectedOutput, $parsedData);
    }
}
