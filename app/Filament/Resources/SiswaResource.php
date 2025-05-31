<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\SiswaResource\RelationManagers;
use App\Models\Siswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

     // Tambahan untuk ganti label
    protected static ?string $pluralLabel = 'Daftar siswa pkl';
    protected static ?string $label = 'Siswa';
    protected static ?string $navigationLabel = 'Siswa';

    protected static ?string $slug = 'siswa';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),


                Forms\Components\TextInput::make('nis')
                    ->label('NIS')
                    ->required()
                    ->maxLength(5),
                Forms\Components\Select::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki', 
                        'Perempuan' => 'Perempuan'
                    ])
                    ->required(),
                Forms\Components\TextInput::make('alamat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('kontak')
                    ->required()
                    ->maxLength(15),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                 Forms\Components\FileUpload::make('foto')
                    ->label('Foto siswa')
                    ->openable()
                    ->image()
                    ->disk('public')
                    ->required()
                    ->directory('foto')       
                    ->previewable(),

                //menambah roles
                // Forms\Components\Select::make('roles')  
                //     ->relationship('roles', 'name')
                //     ->preload()
                //     ->searchable(),
                 
                // Forms\Components\Toggle::make('status_pkl')
                //     ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                    Tables\Columns\ImageColumn::make('foto')
                        ->label('Foto')
                        ->circular()
                        ->size(40),
                
                    Tables\Columns\TextColumn::make('nama')
                        ->searchable()
                        ->weight('medium'),
                
                    Tables\Columns\TextColumn::make('nis')
                        ->label('NIS')
                        ->searchable(),
                
                    Tables\Columns\TextColumn::make('alamat')->searchable(),
                    Tables\Columns\TextColumn::make('kontak')->searchable(),
                    Tables\Columns\TextColumn::make('email')->searchable(),
                
                    Tables\Columns\IconColumn::make('status_pkl')->boolean(),
                ])
                
                // Tables\Columns\TextColumn::make('roles')
                //     ->label('Role')
                //     ->formatStateUsing(function ($state, $record) {
                //         return $record->getRoleNames()->join(', ');
                //     }),

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSiswas::route('/'),
        ];
    }
}