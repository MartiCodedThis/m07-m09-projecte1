<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use App\Models\User;
use App\Models\File;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use LiveWire;
use Filament\Forms\Components;
use Filament\Tables\Columns\TextColumn;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-annotation';

    public static function form(Form $form): Form
    {
        $authors = User::all()->pluck('name', 'id');
        $user = Auth::user();

        return $form
            ->schema([
                Components\Fieldset::make('File')
                    ->translateLabel()
                    ->relationship('file')
                    ->saveRelationshipsWhenHidden()
                    ->schema([
                        // Declara un camp de pujada de fitxer al formulari
                        Components\FileUpload::make('filepath')
                        // Fa que el valor del camp sigui requerit
                        ->required()
                        // Especifica que s'ha d'emplenar amb una imatge
                        ->image()
                        // Limita el tamany de pujada a 2MB
                        ->maxSize(2048)
                        // Indica a on es pujarà l'arxiu    
                        ->directory('uploads')
                        // Modifica el nom de l'arxiu per incloure el temps de la pujada
                        ->getUploadedFileNameForStorageUsing(function (Livewire\TemporaryUploadedFile $file): string {
                            return time() . '_' . $file->getClientOriginalName();
                        })
                        ->label(__('File path')),
                    ]),
                Components\Fieldset::make('Post')
                    ->translateLabel()
                    ->schema([
                        Components\Hidden::make('file_id')
                            ->label(__('File ID')),
                        Components\Select::make('author_id')
                            ->required()
                            ->options($authors)
                            ->default($user->id)
                            ->label(__('Author ID')),
                        Components\TextInput::make('body')
                            ->required()
                            ->maxLength(255)
                            ->translateLabel(),
                        Components\TextInput::make('latitude')
                            ->required()
                            ->translateLabel(),
                        Components\TextInput::make('longitude')
                            ->required()
                            ->translateLabel(),
                    ]),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('file_id')
                    ->label(__('File ID')),
                Tables\Columns\TextColumn::make('author_id')
                    ->label(__('Author ID')),
                Tables\Columns\TextColumn::make('body')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('latitude')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('longitude')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->translateLabel(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }    
}
