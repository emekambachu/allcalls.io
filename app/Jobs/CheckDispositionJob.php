<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Notifications\ClientDispositionReminder;

class CheckDispositionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $user;
    public $client;
    public $uniqueCallId;
    public $tries = 0; // to keep track of the number of attempts

    // public function __construct(User $user)
    // {
    //     $this->user = $user;
    // }

    public function __construct(User $user, Client $client, $uniqueCallId)
    {
        $this->user = $user;
        $this->client = $client;
        $this->uniqueCallId = $uniqueCallId;
    }



    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            if ($this->tries < 10) {
                // Log the attempt
                Log::info("Checking disposition for client {$this->client->id} on attempt {$this->tries}");
    
                // Check the client's disposition status
                if ($this->client->status === null || $this->client->status === '' || $this->client->status == 'not_sold') {
                    // The status is still not set, and the max number of attempts hasn't been reached
                    
                    // Send a reminder notification to the client
                    $this->user->notify(new ClientDispositionReminder());
                    Log::info("Notification sent to client {$this->client->id} for call {$this->uniqueCallId}");
    
                    // Increment the number of tries
                    $this->tries++;
    
                    // Re-dispatch the job with a 15-second delay
                    self::dispatch($this->user, $this->client, $this->uniqueCallId)->delay(now()->addSeconds(15));
                } else {
                    // Log when the status is set
                    Log::info("Disposition status set for client {$this->client->id} - {$this->client->status}");
                }
            } else {
                // Log when max attempts are reached
                Log::warning("Max attempts reached for client {$this->client->id} without disposition being set");
            }
        } catch (\Exception $e) {
            // Log any exceptions
            Log::error("Error checking disposition for client {$this->client->id}: {$e->getMessage()}");
            
            // Optionally, re-throw the exception if you want it to bubble up:
            // throw $e;
        }
    }
}
