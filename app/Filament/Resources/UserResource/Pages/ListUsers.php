<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'All users' => Tab::make(),
            'Admins' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->whereHas('roles', fn ($query) => $query->where('name', 'admin'))),
            'Internal Agents' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->whereHas('roles', fn ($query) => $query->where('name', 'internal-agent'))),
        ];
    }
}
