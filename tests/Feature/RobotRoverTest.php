<?php

namespace Tests\Feature;

use App\Models\Plateau;
use App\Models\RobotRover;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RobotRoverTest extends TestCase
{
    public function test_move_functions()
    {
        $plateau = new Plateau(['sizeX' => 10, 'sizeY' => 5]);

        $robot = new RobotRover([
            'plateau' => $plateau,
        ]);

        // assert Initialization
        $this->assertEquals(0, $robot->getXCoordinate());
        $this->assertEquals(0, $robot->getYCoordinate());
        $this->assertEquals('N', $robot->getDirection());

        $robot->moveToN(4);
        $this->assertEquals(4, $robot->getYCoordinate());
        $robot->moveToS(2);
        $this->assertEquals(2, $robot->getYCoordinate());
        $robot->moveToS(5);
        $this->assertEquals(0, $robot->getYCoordinate());
        $robot->moveToN(100);
        $this->assertEquals($plateau->getYLength(), $robot->getYCoordinate());
        $robot->moveToS();
        $this->assertEquals(4, $robot->getYCoordinate());
        $robot->moveToN();
        $this->assertEquals(5, $robot->getYCoordinate());

        $robot->moveToE(4);
        $this->assertEquals(4, $robot->getXCoordinate());
        $robot->moveToW(2);
        $this->assertEquals(2, $robot->getXCoordinate());
        $robot->moveToW(5);
        $this->assertEquals(0, $robot->getXCoordinate());
        $robot->moveToE(100);
        $this->assertEquals($plateau->getXLength(), $robot->getXCoordinate());
        $robot->moveToW();
        $this->assertEquals(9, $robot->getXCoordinate());
        $robot->moveToE();
        $this->assertEquals(10, $robot->getXCoordinate());
    }

    public function test_calc_next_step()
    {
        $plateau = new Plateau(['sizeX' => 3, 'sizeY' => 3]);

        $robot = new RobotRover([
            'plateau' => $plateau,
        ]);

        $robot->calcNextStep('R', true);
        $this->assertEquals(1, $robot->getXCoordinate());
        $this->assertEquals(0, $robot->getYCoordinate());
        $this->assertEquals('E', $robot->getDirection());

        $robot->calcNextStep('L', false);
        $this->assertEquals(1, $robot->getXCoordinate());
        $this->assertEquals(0, $robot->getYCoordinate());
        $this->assertEquals('N', $robot->getDirection());

        $robot->calcNextStep('', true);
        $this->assertEquals(1, $robot->getXCoordinate());
        $this->assertEquals(1, $robot->getYCoordinate());
        $this->assertEquals('N', $robot->getDirection());

        $robot->calcNextStep('', true, 5);
        $this->assertEquals(1, $robot->getXCoordinate());
        $this->assertEquals(3, $robot->getYCoordinate());
        $this->assertEquals('N', $robot->getDirection());

        $robot->calcNextStep('R', true, 5);
        $this->assertEquals(3, $robot->getXCoordinate());
        $this->assertEquals(3, $robot->getYCoordinate());
        $this->assertEquals('E', $robot->getDirection());

        $robot->calcNextStep('L', false);
        $robot->calcNextStep('L', false);
        $robot->calcNextStep('', true, 5);
        $this->assertEquals(0, $robot->getXCoordinate());
        $this->assertEquals(3, $robot->getYCoordinate());
        $this->assertEquals('W', $robot->getDirection());
    }

    public function test_go_by_instruction()
    {
        $plateau = new Plateau(['sizeX' => 10, 'sizeY' => 10]);

        $robot = new RobotRover([
            'plateau' => $plateau,
            'currentDirection' => 'N',
            'xCoordinate' => 4,
            'yCoordinate' => 4
        ]);

        $instructions = ['M', 'M', 'L', 'M', 'R', 'R', 'M'];

        $robot->moveByInstructions($instructions);

        $this->assertEquals('E', $robot->getDirection());
        $this->assertEquals(4, $robot->getXCoordinate());
        $this->assertEquals(6, $robot->getYCoordinate());

        // assert the robot does not cross the border of the plateau
        $robot = new RobotRover([
            'plateau' => $plateau,
            'currentDirection' => 'E',
            'xCoordinate' => 9,
            'yCoordinate' => 9
        ]);
        $instructions = ['M', 'M', 'M'];

        $robot->moveByInstructions($instructions);

        $this->assertEquals('E', $robot->getDirection());
        $this->assertEquals(10, $robot->getXCoordinate());
        $this->assertEquals(9, $robot->getYCoordinate());

        // assert the robot does not cross the border of the plateau
        $robot = new RobotRover([
            'plateau' => $plateau,
            'currentDirection' => 'E',
            'xCoordinate' => 1,
            'yCoordinate' => 1
        ]);
        $instructions = ['L', 'L', 'M', 'M', 'M', 'L'];

        $robot->moveByInstructions($instructions);
        $this->assertEquals('S', $robot->getDirection());
        $this->assertEquals(0, $robot->getXCoordinate());
        $this->assertEquals(1, $robot->getYCoordinate());
    }

    public function test_output_status()
    {
        $robot = new RobotRover([
            'currentDirection' => 'E',
            'xCoordinate' => 1,
            'yCoordinate' => 2
        ]);

        $this->assertEquals("1 2 E", $robot->outputStatus());
    }
}
