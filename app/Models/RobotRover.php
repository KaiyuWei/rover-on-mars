<?php

namespace App\Models;

use App\Exceptions\InvalidTurnToException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RobotRover extends Model
{
    const DIRECTION_ENUM = ['N', 'W', 'S', 'E'];

    const TURN_TO_ENUM = ['L', 'R'];

    const DIRECTION_TRANSFORMER = [
        ['N' => ['L' => 'W', 'R' => 'E']],
        ['W' => ['L' => 'S', 'R' => 'N']],
        ['S' => ['L' => 'E', 'R' => 'W']],
        ['E' => ['L' => 'N', 'R' => 'S']]
    ];

    public function __construct(
        protected string $currentDirection,
        protected array $currentCoordinate
    )
    {}

    private function checkTurnToValue(string $input): bool
    {
        return in_array($input, static::TURN_TO_ENUM);
    }

    private function checkDirectionValue(string $input): bool
    {
        return in_array($input, static::DIRECTION_ENUM);
    }
}
