<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Joaopaulolndev\FilamentEditProfile\Concerns\HasSort;

class CustomProfileComponent extends Component implements HasForms
{
    use InteractsWithForms;
    use HasSort;

    public $data = [];

    protected static int $sort = 0;

    public function mount(): void
    {
        $this->data = [
            'nik' => auth()->user()->nik,
            'phone' => auth()->user()->phone,
            'dusun' => auth()->user()->dusun,
            'rt' => auth()->user()->rt,
            'rw' => auth()->user()->rw,
            // 'foto_ktp' => auth()->user()->foto_ktp,
        ];
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Identitas Pribadi')
                    ->aside()
                    ->description('Silahkan lengkapi data-data berikut ini untuk keperluan administrasi.')
                    ->schema([
                        Grid::make(2)->schema([                            
                            TextInput::make('nik')
                                ->label('NIK')
                                ->rule('digits:16')
                                ->validationMessages([
                                    'rule' => 'NIK harus terdiri dari 16 digit.',
                                ])
                                ->maxLength(16),
    
                            TextInput::make('phone')
                                ->label('No. Telepon')
                                ->tel()
                                ->numeric()
                                ->minLength(10)
                                ->maxLength(13),
                        ]),

                        Grid::make(4)->schema([
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
                            TextInput::make('usia')
                                ->label('Usia')
                                ->required()
                                ->numeric()
                                ->maxLength(3),
                        ]),

                        FileUpload::make('foto_ktp')
                            ->label('Foto KTP')
                            ->directory('ktp')
                            ->image()
                            ->visibility('public'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $user = auth()->user();

        $user->update([
            'nik' => $data['nik'],
            'phone' => $data['phone'],
            'dusun' => $data['dusun'],
            'rt' => $data['rt'],
            'rw' => $data['rw'],
            'foto_ktp' => $data['foto_ktp'],
        ]);

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
