<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Resources\SiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSiswas extends ManageRecords
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // Tambahkan method ini
    protected function saved($record): void
    {
        // Pastikan relasi ke user tersedia
        if ($record->user) {
            // Kosongkan email user atau sesuaikan dengan kebutuhan
            $record->user->email = null;
            $record->user->save();
        }
    }
}
