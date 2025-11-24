<?php

namespace App\Filament\Resources\WasteTypes\Pages;

use App\Filament\Resources\WasteTypes\WasteTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageWasteTypes extends ManageRecords
{
    protected static string $resource = WasteTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Jenis Sampah')
                ->modalWidth('md')
                ->modalSubmitActionLabel('Simpan')
                ->modalHeading('Buat Jenis Sampah')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
