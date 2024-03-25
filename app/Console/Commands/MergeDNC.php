<?php

namespace App\Console\Commands;

use App\Models\DeltaExecution;
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
        Log::debug('Starting DNC merge...');

        $this->info('Starting Node.js script...');
        $cmd = 'node ~/node-delta/index';
        $descriptorSpec = [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "w"]
        ];

        $process = proc_open($cmd, $descriptorSpec, $pipes);
        $output = ''; // Variable to accumulate output

        if (is_resource($process)) {
            while ($line = fgets($pipes[1])) {
                $trimmedLine = trim($line);
                Log::debug($trimmedLine);
                $this->line($trimmedLine); // Output to terminal
                $output .= $trimmedLine . PHP_EOL; // Accumulate the output
            }

            fclose($pipes[1]);

            while ($errLine = fgets($pipes[2])) {
                $trimmedErrLine = trim($errLine);
                Log::error($trimmedErrLine);
                $this->error($trimmedErrLine); // Output errors to terminal
                $output .= $trimmedErrLine . PHP_EOL; // Accumulate the error output
            }

            fclose($pipes[2]);
            $return_value = proc_close($process);

            // Determine the status based on the output content
            $status = str_contains($output, "All lines have been processed") ? "Successful" : "Unsuccessful";

            // Save the accumulated output to the database
            $deltaExecution = new DeltaExecution();
            $deltaExecution->output = $output;
            $deltaExecution->status = $status; // Assuming you have a 'status' column
            $deltaExecution->save();

            $this->info('Node.js script execution completed.');
        } else {
            $this->error('Failed to execute Node.js script.');
        }
    }
}
