<?php

namespace App\Models;

use App\Exceptions\InvalidTurnToException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

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

    public function moveToN(int $step = 1): void
    {
        if ($this->plateau->ifWithinBorder('Y', $this->yCoordinate + $step))
        {
            $this->yCoordinate += $step;
        }
        else {
            $this->yCoordinate = $this->plateau->getYLength();
        }
    }

    public function moveToS(int $step = 1): void
    {
        if ($this->plateau->ifWithinBorder('Y', $this->yCoordinate - $step))
        {
            $this->yCoordinate -= $step;
        }
        else {
            $this->yCoordinate = 0;
        }
    }

    public function moveToW(int $step = 1): void
    {
        if ($this->plateau->ifWithinBorder('X', $this->xCoordinate - $step))
        {
            $this->xCoordinate -= $step;
        }
        else {
            $this->xCoordinate = 0;
        }
    }

    public function moveToE(int $step = 1): void
    {
        if ($this->plateau->ifWithinBorder('X', $this->xCoordinate + $step))
        {
            $this->xCoordinate += $step;
        }
        else {
            $this->xCoordinate = $this->plateau->getXLength();
        }
    }

    public function getXCoordinate(): int
    {
        return $this->xCoordinate;
    }

    public function getYCoordinate(): int
    {
        return $this->yCoordinate;
    }

    public function getDirection(): string{
        return $this->currentDirection;
    }
}
