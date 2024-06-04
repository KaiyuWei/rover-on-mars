<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plateau extends Model
{
    use HasFactory;

    protected int $sizeX;

    protected int $sizeY;

    protected $fillable= ['sizeX', 'sizeY'];

    public function __construct(protected $attributes = [])
    {
        parent::__construct($attributes);

        $this->sizeX = $attributes['sizeX'] ?? 0;
        $this->sizeY = $attributes['sizeY'] ?? 0;
    }

    public function ifWithinBorder(string $axis, int $Coordinate): bool
    {
        $axis = strtoupper($axis);
        $property = 'size' . $axis;
        return ($Coordinate <= $this->$property) && ($Coordinate >= 0);
    }

    public function getXLength(): int
    {
        return $this->sizeX;
    }

    public function getYLength(): int
    {
        return $this->sizeY;
    }
}
