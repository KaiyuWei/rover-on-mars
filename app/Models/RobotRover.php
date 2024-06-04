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
        'N' => ['L' => 'W', 'R' => 'E'],
        'W' => ['L' => 'S', 'R' => 'N'],
        'S' => ['L' => 'E', 'R' => 'W'],
        'E' => ['L' => 'N', 'R' => 'S']
    ];

    protected ?Plateau $plateau;

    protected string $currentDirection;

    protected int $xCoordinate;

    protected int $yCoordinate;

    protected $fillable = ['plateau', 'currentDirection', 'xCoordinate', 'yCoordinate'];

    public function __construct(protected $attributes = [])
    {
        parent::__construct($attributes);

        $this->plateau = $attributes['plateau'] ?? null;
        $this->currentDirection = $attributes['currentDirection'] ?? 'N';
        $this->xCoordinate = $attributes['xCoordinate'] ?? 0;
        $this->yCoordinate = $attributes['yCoordinate'] ?? 0;
    }

    public function calcNextStep(string $turnTo, bool $shouldMove)
    {
        if (!$this->checkTurnToValue($turnTo))
        {
            throw new InvalidTurnToException("The turn-to direction is invalid.");
        }

        $nextDirection = self::DIRECTION_TRANSFORMER[$this->currentDirection][$turnTo];

        if ($shouldMove)
        {
            $next = ('moveTo' . $nextDirection)();


        }
        else
        {
            $this->currentDirection = $nextDirection;
        }
    }

    private function checkTurnToValue(string $input): bool
    {
        return in_array($input, self::TURN_TO_ENUM);
    }

    private function checkDirectionValue(string $input): bool
    {
        return in_array($input, self::DIRECTION_ENUM);
    }

    private function moveToN(): array
    {
        return ['axis' => 'Y', 'value' => $this->yCoordinate + 1];
    }

    private function moveToS(): array
    {
        return ['axis' => 'Y', 'value' => $this->yCoordinate - 1];
    }

    private function moveToW(): array
    {
        return ['axis' => 'X', 'value' => $this->xCoordinate - 1];
    }

    private function moveToE(): array
    {
        return ['axis' => 'X', 'value' => $this->xCoordinate + 1];
    }
}
