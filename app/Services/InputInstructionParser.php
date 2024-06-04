<?php

namespace App\Services;

class InputInstructionParser
{
    static public function parseMarsRoverInput($input): array
    {
        $lines = explode("\n", $input);
        $lines = array_map('trim', $lines);

        $result = [];

        // get the plateau size from the first line
        $plateauSize = explode(' ', array_shift($lines));

        $result['plateau'] = [
            'x' => (int)$plateauSize[0],
            'y' => (int)$plateauSize[1]
        ];

        // process each robot's information
        for ($i = 0; $i < count($lines); $i += 2) {
            if (isset($lines[$i]) && isset($lines[$i + 1])) {

                // get the initial position of the rover
                $position = explode(' ', $lines[$i]);

                $initialPosition = [
                    'x' => (int)$position[0],
                    'y' => (int)$position[1],
                    'direction' => $position[2]
                ];

                // get the movement instructions
                $movements = str_split($lines[$i + 1]);

                // Add the rover information to the result array
                $result['robots'][] = [
                    'initial position' => $initialPosition,
                    'movement' => $movements
                ];
            }
        }

        return $result;
    }
}
