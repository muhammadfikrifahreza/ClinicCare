<?php

namespace App\Filament\Resources\PatientAppointmentResource\Pages;

use App\Filament\Resources\PatientAppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPatientAppointments extends ListRecords
{
    protected static string $resource = PatientAppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
