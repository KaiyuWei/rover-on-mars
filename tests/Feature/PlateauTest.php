<?php

namespace Tests\Feature;

use App\Models\Plateau;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PlateauTest extends TestCase
{
    public function test_if_within_border()
    {
        $plateau = new Plateau(['sizeX' => 10, 'sizeY' => 5]);

        $this->assertTrue($plateau->ifWithinBorder('X', 8));
        $this->assertFalse($plateau->ifWithinBorder('X', 12));
        $this->assertFalse($plateau->ifWithinBorder('X', -3));
        $this->assertTrue($plateau->ifWithinBorder('Y', 4));
        $this->assertFalse($plateau->ifWithinBorder('Y', -3));
        $this->assertFalse($plateau->ifWithinBorder('Y', 8));
    }
}
