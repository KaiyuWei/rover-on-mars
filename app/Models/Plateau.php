<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plateau extends Model
{
    use HasFactory;

    public function __construct(
        protected int $sizeX,
        protected int $sizeY
    )
    {
        parent::__construct([]);
    }

    public function ifWithinBorder(int $xCoordinate, int $yCoordinate): bool
    {
        return ($xCoordinate <= $this->sizeX) && ($yCoordinate <= $this->sizeY);
    }
}
