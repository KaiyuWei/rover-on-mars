<?php

namespace App\Services;

class InputInstructionParser
{
    static public function parseMarsRoverInput(string $input): array
    {
        $lines = trim($input);
        $lines = explode("\n", $lines);
        $lines = array_map('trim', $lines);

        $result = [];

        // get the plateau size from the first line
        $plateauSize = explode(' ', array_shift($lines));

        $result['plateau'] = [
            'sizeX' => (int)$plateauSize[0],
            'sizeY' => (int)$plateauSize[1]
        ];

        // process each robot's information
        for ($i = 0; $i < count($lines); $i += 2) {
            if (isset($lines[$i]) && isset($lines[$i + 1])) {

                // get the initial position of the rover
                $position = explode(' ', $lines[$i]);

                $initialPosition = [
                    'xCoordinate' => (int)$position[0],
                    'yCoordinate' => (int)$position[1],
                    'currentDirection' => $position[2]
                ];

                // get the movement instructions
                $movements = str_split($lines[$i + 1]);

                // Add the rover information to the result array
                $result['robots'][] = [
                    'initial' => $initialPosition,
                    'movement' => $movements
                ];
            }
        }

        return $result;
    }
}
