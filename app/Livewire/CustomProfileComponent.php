<?php

namespace App\Livewire;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Joaopaulolndev\FilamentEditProfile\Concerns\HasSort;
use Livewire\Component;

class CustomProfileComponent extends Component implements HasForms
{
    use InteractsWithForms;
    use HasSort;

    public ?array $data = [];

    protected static int $sort = 0;

    public function mount(): void
    {
        $this->data = [
            'nik' => auth()->user()->nik,
            'phone' => auth()->user()->phone,
            'dusun' => auth()->user()->dusun,
            'rt' => auth()->user()->rt,
            'rw' => auth()->user()->rw,
        ];
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Identitas (Wajib Diisi)')
                    ->aside()
                    ->description('Silahkan lengkapi data-data berikut ini untuk keperluan administrasi.')
                    ->schema([
                        TextInput::make('nik')
                            ->label('NIK')
                            ->required()
                            ->rule('digits:16')
                            ->unique('users', 'nik', ignoreRecord: true)
                            ->validationMessages([
                                'unique' => 'NIK ini sudah terdaftar.',
                            ])
                            ->maxLength(16),

                        TextInput::make('phone')
                            ->label('No. Telepon')
                            ->tel()
                            ->required()
                            ->numeric()
                            ->minLength(10)
                            ->maxLength(13),

                        // Dusun + RT + RW dalam 1 baris
                        Grid::make(3)->schema([
                            TextInput::make('dusun')
                                ->label('Dusun')
                                ->required(),
                            TextInput::make('rt')
                                ->label('RT')
                                ->required()
                                ->numeric()
                                ->maxLength(3),
                            TextInput::make('rw')
                                ->label('RW')
                                ->required()
                                ->numeric()
                                ->maxLength(3),
                        ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        // Ambil data hasil form (udah valid)
        $data = $this->form->getState();

        $user = auth()->user();

        $user->update([
            'nik' => $data['nik'],
            'phone' => $data['phone'],
            'dusun' => $data['dusun'],
            'rt' => $data['rt'],
            'rw' => $data['rw'],
        ]);

        // Optional notif ala Filament
        \Filament\Notifications\Notification::make()
            ->title('Data berhasil disimpan!')
            ->success()
            ->send();
    }


    public function render(): View
    {
        return view('livewire.custom-profile-component');
    }
}
