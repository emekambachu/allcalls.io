<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('first_name')->sortable(),
                TextColumn::make('last_name')->sortable(),
                TextColumn::make('balance')->sortable()->money(),
                TextColumn::make('email')->sortable(),
                TextColumn::make('phone')->sortable(),
                TextColumn::make('progress'),
                TextColumn::make('level.name')->label('Level')->sortable(),
                TextColumn::make('invitedBy')->label('Upline')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->invitedBy ? "{$record->invitedBy->first_name} {$record->invitedBy->last_name}" : '-';
                    })
                    ->sortable(),
                TextColumn::make('created_at')->sortable()->dateTime(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('Registered from'),
                        Forms\Components\DatePicker::make('created_until')->label('Registered to'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),

                Filter::make('email')
                    ->form([
                        Forms\Components\TextInput::make('email')
                            ->placeholder('Search by email')
                            ->label('Email'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['email'],
                                fn (Builder $query, $email): Builder => $query->where('email', 'like', "%{$email}%"),
                            );
                    }),
                Filter::make('first_name')
                    ->form([
                        Forms\Components\TextInput::make('first_name')
                            ->placeholder('Search by first name')
                            ->label('First name'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['first_name'],
                                fn (Builder $query, $firstName): Builder => $query->where('first_name', 'like', "%{$firstName}%"),
                            );
                    }),
                Filter::make('last_name')
                    ->form([
                        Forms\Components\TextInput::make('last_name')
                            ->placeholder('Search by last name')
                            ->label('Last name'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['last_name'],
                                fn (Builder $query, $lastName): Builder => $query->where('first_name', 'like', "%{$lastName}%"),
                            );
                    }),
                Filter::make('phone')
                    ->form([
                        Forms\Components\TextInput::make('phone')
                            ->placeholder('Search by phone')
                            ->label('Phone'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['phone'],
                                fn (Builder $query, $phone): Builder => $query->where('first_name', 'like', "%{$phone}%"),
                            );
                    }),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('approve')
                        ->requiresConfirmation()
                        ->icon('heroicon-o-check-circle')
                        ->action(function (User $user) {
                            $user->is_locked = false;
                            $user->save();
                        })->after(function () {
                            Notification::make()->success()->title('User approved')->body('The user has been approved.')->send();
                        }),
                    Tables\Actions\Action::make('update_progress')
                        ->form([
                            Forms\Components\Select::make('progress')
                                ->options([
                                    'Missing Information' => 'Missing Information',
                                    'Needs ICA' => 'Needs ICA',
                                    'Contracting Reviewing Documents' => 'Contracting Reviewing Documents',
                                    'Contracting Missing AML' => 'Contracting Missing AML',
                                    'Contracting Missing Resident License' => 'Contracting Missing Resident License',
                                    'Contracting Missing Banking Info' => 'Contracting Missing Banking Info',
                                    'Carrier Contracts Sent to Agent' => 'Carrier Contracts Sent to Agent',
                                    'Agent Signed Carrier Contracts' => 'Agent Signed Carrier Contracts',
                                    'Contracts Sent to Carrier' => 'Contracts Sent to Carrier',
                                ])
                                ->label('Progress'),
                        ])
                        ->icon('heroicon-s-clipboard-document-check')
                        ->action(function (User $user, array $data) {
                            // approve the user here
                            $user->progress = $data['progress'];
                            $user->save();
                        })->after(function () {
                            Notification::make()->success()->title('User progress updated')->body('The user\'s progress has been updated.')->send();
                        }),


                    Tables\Actions\Action::make('view_pdfs')
                        ->modalSubmitAction(false) // Remove Submit Button
                        ->modalCancelAction(false) // Remove Cancel Button
                        ->infolist([
                            // Example for one PDF, repeat for each PDF
                            Section::make('PDFs ready to be downloaded:')
                                ->schema([
                                    // Map each relationship to its corresponding URL attribute
                                    TextEntry::make('internalAgentContract.getContractSign.sign_url')
                                        ->label('Full Contract URL'),
                                    TextEntry::make('internalAgentContract.driverLicense.url')
                                        ->label('Driver License URL'),
                                    TextEntry::make('internalAgentContract.amlCourse.url')
                                        ->label('AML Course URL'),
                                    TextEntry::make('internalAgentContract.errorAndEmission.url')
                                        ->label('Errors and Omissions Insurance URL'),
                                    TextEntry::make('internalAgentContract.residentLicense.url')
                                        ->label('Resident License URL'),
                                    TextEntry::make('internalAgentContract.bankingInfo.url')
                                        ->label('Banking Information URL'),

                                ]),
                            // Repeat sections for additional PDFs...
                        ])
                        ->icon('heroicon-o-document')
                        ->action(function (User $user, array $data) {
                            // Optional action when PDF is selected



                        }),



                ])



            ]);
        // ->bulkActions([
        //     Tables\Actions\BulkActionGroup::make([
        //         Tables\Actions\DeleteBulkAction::make(),
        //     ]),
        // ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
