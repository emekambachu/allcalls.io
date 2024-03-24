<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MergeDNC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'allcalls:merge-dnc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Merge the DNC delta file for the day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Node.js script...');

        $cmd = 'node ~/node-delta/index';

        $descriptorSpec = array(
            0 => array("pipe", "r"),  // stdin
            1 => array("pipe", "w"),  // stdout
            2 => array("pipe", "w")   // stderr
        );

        $process = proc_open($cmd, $descriptorSpec, $pipes);

        if (is_resource($process)) {
            while ($line = fgets($pipes[1])) {
                $trimmedLine = trim($line);
                Log::debug($trimmedLine);
                $this->line($trimmedLine); // Output to terminal
            }

            fclose($pipes[1]);

            // Optionally, handle stderr as well
            while ($errLine = fgets($pipes[2])) {
                $trimmedErrLine = trim($errLine);
                Log::error($trimmedErrLine);
                $this->error($trimmedErrLine); // Output errors to terminal
            }
            fclose($pipes[2]);

            $return_value = proc_close($process);

            $this->info('Node.js script execution completed.');
        } else {
            $this->error('Failed to execute Node.js script.');
        }
    }
}
