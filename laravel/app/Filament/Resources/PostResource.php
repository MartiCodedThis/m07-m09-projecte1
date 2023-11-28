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
                        }),
                    ]),
                Components\Fieldset::make('Post')
                    ->schema([
                        Components\Hidden::make('file_id')
                            ->required(),
                        Components\Select::make('author_id')
                            ->required()
                            ->options($authors)
                            ->default($user->id),
                        Components\TextInput::make('body')
                            ->required()
                            ->maxLength(255),
                        Components\TextInput::make('latitude')
                            ->required(),
                        Components\TextInput::make('longitude')
                            ->required(),
                    ]),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('file_id'),
                Tables\Columns\TextColumn::make('author_id'),
                Tables\Columns\TextColumn::make('body'),
                Tables\Columns\TextColumn::make('latitude'),
                Tables\Columns\TextColumn::make('longitude'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
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
