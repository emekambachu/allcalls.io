<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use App\Filament\Resources\UserResource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

class CreateUser extends CreateRecord
{
    use HasWizard;

    protected static string $resource = UserResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('Basic information')
                ->description('Add some basic details')
                ->schema([
                    TextInput::make('first_name')
                        ->required(),
                    TextInput::make('last_name')
                        ->required(),
                    TextInput::make('email')
                        ->required(),
                ]),
            Step::make('State licenses')
                ->description('Add some extra details')
                ->schema([]),
        ];
    }
}
