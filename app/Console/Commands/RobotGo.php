<?php

namespace App\Console\Commands;

use App\Exceptions\UnrecognizedInstruction;
use App\Models\Plateau;
use App\Models\RobotRover;
use Illuminate\Console\Command;
use App\Services\InputInstructionParser;

class RobotGo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'robot:go {--file=} {--string=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Control the robot movement by instructions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $inputString = '';
        if ($this->option('file'))
        {
            $inputFilePath = $this->option('file');
            $inputString = file_get_contents($inputFilePath);
        }
        elseif($this->option('string'))
        {
            $inputString = $this->option('string');
        }
        else
        {
            // use the default file path
            $inputFilePath = env('DEFAULT_INPUT_FILE_PATH');
            $inputString = file_get_contents($inputFilePath);
        }

        $inputArray = InputInstructionParser::parseMarsRoverInput($inputString);

        $plateau = new Plateau($inputArray['plateau']);

        $finalStatuses = [];

        // create robot instances
        foreach ($inputArray['robots'] as $instruction)
        {
            $robot = new RobotRover(array_merge($instruction['initial'], ['plateau' => $plateau]));
            try
            {
                $robot->moveByInstructions($instruction['movement']);
                $finalStatuses[] = $robot->outputStatus();
            } catch (UnrecognizedInstruction $e) {
                $this->error($e->getMessage());
            }
        }

        $outputString = implode(PHP_EOL, $finalStatuses);
        $this->info($outputString);
    }
}
