<?php

namespace App\Models;

use App\Exceptions\UnrecognizedInstruction;
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

        $this->plateau = $attributes['plateau'] ?? new Plateau(['sizeX' => (int)INF, 'sizeY' => (int)INF]);
        $this->currentDirection = $attributes['currentDirection'] ?? 'N';
        $this->xCoordinate = $attributes['xCoordinate'] ?? 0;
        $this->yCoordinate = $attributes['yCoordinate'] ?? 0;
    }

    public function calcNextStep(string $turnTo = '', bool $shouldMove = false, int $step = 1): void
    {
        if ($turnTo)
        {
            $this->currentDirection = self::DIRECTION_TRANSFORMER[$this->currentDirection][$turnTo];
        }

        if ($shouldMove)
        {
            $moveHandler = 'moveTo' . $this->currentDirection;
            $this->$moveHandler($step);
        }
    }

    public function moveByInstructions(array $instructions): void
    {
        foreach($instructions as $instruction)
        {
            if (in_array($instruction, self::TURN_TO_ENUM))
            {
                $this->currentDirection = self::DIRECTION_TRANSFORMER[$this->currentDirection][$instruction];
            }
            elseif ($instruction === 'M')
            {
                $moveHandler = 'moveTo' . $this->currentDirection;
                $this->$moveHandler();
            }
            else
            {
                throw new UnrecognizedInstruction(sprintf('The instruction "%s" cannot be recognized', $instruction));
            }
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

    public function outputStatus(): string
    {
        return sprintf('%s %s %s', (string)$this->xCoordinate, (string)$this->yCoordinate, $this->currentDirection);
    }

}
