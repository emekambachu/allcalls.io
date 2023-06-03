<?php

namespace App\Models;

use App\Models\State;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserCallTypeState extends Pivot
{
    use HasFactory;

    protected $table = 'users_call_type_state';

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public static function forUser($user)
    {
        return self::where('user_id', $user->id)->get();
    }

    public static function updateUserCallTypeState($user, $incomingData)
    {
        // Fetch current state for the user
        $existingData = self::where('user_id', $user->id)
            ->get()
            ->map(function ($record) {
                return [
                    'call_type_id' => $record->call_type_id,
                    'state_id' => $record->state_id
                ];
            })->toArray();

        // Calculate difference
        $recordsToDelete = array_udiff($existingData, $incomingData, function ($a, $b) {
            return $a['call_type_id'] - $b['call_type_id'] ?: $a['state_id'] - $b['state_id'];
        });

        $recordsToInsert = array_udiff($incomingData, $existingData, function ($a, $b) {
            return $a['call_type_id'] - $b['call_type_id'] ?: $a['state_id'] - $b['state_id'];
        });

        // Perform database operations
        DB::transaction(function () use ($user, $recordsToDelete, $recordsToInsert) {
            // Delete records
            foreach ($recordsToDelete as $record) {
                self::where('user_id', $user->id)
                    ->where('call_type_id', $record['call_type_id'])
                    ->where('state_id', $record['state_id'])
                    ->delete();
            }

            // Insert new entries
            foreach ($recordsToInsert as $record) {
                $record['user_id'] = $user->id;
                $record['created_at'] = now();
                $record['updated_at'] = now();
                self::insert($record);
            }
        });
    }
}