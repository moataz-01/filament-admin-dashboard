<?php

namespace App\Filament\Resources;

use App\Filament\Exports\CategoryExporter;
use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Filament\Resources\CategoryResource\RelationManagers\ParentRelationManager;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Concerns\Translatable;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\HeaderActionsPosition;
use Filament\Tables\Enums\ActionsPosition;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class CategoryResource extends Resource
{
    use Translatable;

    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Category';
    protected static ?string $pluralModelLabel = 'Categories';

    public static function getModelLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'قسم' : static::$modelLabel;
    }

    public static function getPluralModelLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'اقسام' : static::$pluralModelLabel;
    }

    public static function form(Form $form): Form
    {
        $categories = Category::query()
            ->when(
                $form->getRecord(),
                fn(Builder $query) => $query->where('id', '!=', $form->getRecord()->id)
            )
            ->pluck('name', 'id');
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(trans('Name'))
                    ->required()
                    ->translatable(true, ['en' => trans('English'), 'ar' => trans('Arabic')], [
                        'en' => ['required', 'string', 'max:255', 'regex:/^[\s\p{Latin}0-9]+$/u'],
                        'ar' => ['required', 'string', 'max:255', 'regex:/^[\s\p{Arabic}0-9]+$/u'],
                    ]),
                Forms\Components\Textarea::make('description')
                    ->label(trans('Description'))
                    ->required()
                    ->translatable(true, ['en' => trans('English'), 'ar' => trans('Arabic')], [
                        'en' => ['required', 'string', 'regex:/^[\s\p{Latin}0-9]+$/u'],
                        'ar' => ['required', 'string', 'regex:/^[\s\p{Arabic}0-9]+$/u'],
                    ]),
                Select::make('parent_id')
                    ->label(trans('Parent'))
                    ->options($categories)
                    ->searchable(),
                SpatieMediaLibraryFileUpload::make('image')
                    ->label(trans('Image'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')
                    ->label(trans('Image')),
                Tables\Columns\TextColumn::make('name')
                    ->label(trans('Name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(trans('Description'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label(trans('Parent'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\LocaleSwitcher::make(),
                ExportAction::make()->exporter(CategoryExporter::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ParentRelationManager::class,
            AuditsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
