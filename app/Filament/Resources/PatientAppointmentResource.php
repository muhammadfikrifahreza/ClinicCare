<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientAppointmentResource\Pages;
use App\Filament\Resources\PatientAppointmentResource\RelationManagers;
use App\Models\PatientAppointment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Auth;

class PatientAppointmentResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = PatientAppointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('doctor_id')
                    ->relationship('doctor', 'name')
                    ->required(),
                Forms\Components\Select::make('patient_id')
                    ->relationship('patient', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\DatePicker::make('appointment_date')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                    'Jadwal sudah dibuat' => 'Jadwal sudah dibuat',
                    'Selesai diperiksa' => 'Selesai diperiksa',
                    'Obat sudah diserahkan' => 'Obat sudah diserahkan',
                     ])
                    ->required(),
                Forms\Components\TextArea::make('note')
                    ->maxLength(255),
                Forms\Components\TextArea::make('prescription')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->modifyQueryUsing(function (builder $query) {
            $isDoctor = auth::user()->hasRole('Doctor');
            $isApoteker = auth::user()->hasRole('Apoteker');
        if ($isDoctor) {
            $userId = auth::user()->id;
            $query->where('doctor_id', $userId);
        }
        if ($isApoteker) {
            $query->where('status', 'Selesai diperiksa');
        }
        })  
        ->columns([
                Tables\Columns\TextColumn::make('doctor.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('patient.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('appointment_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('note')
                    ->searchable(),
                Tables\Columns\TextColumn::make('prescription')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListPatientAppointments::route('/'),
            'create' => Pages\CreatePatientAppointment::route('/create'),
            'edit' => Pages\EditPatientAppointment::route('/{record}/edit'),
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }
}
