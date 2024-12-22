<?php

namespace App\Filament\Resources;

use App\Filament\Exports\CategoryExporter;
use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers\ParentRelationManager;
use App\Models\Category;
use App\Traits\HasTranslatableFields;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Concerns\Translatable;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Actions\ExportAction;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class CategoryResource extends Resource
{
    use Translatable, HasTranslatableFields;

    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('general.category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('general.categories');
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
                Section::make()->schema([
                    Tabs::make('Tabs')
                        ->tabs(self::buildLocaleTabs([
                            ['name' => 'name', 'type' => 'text', 'rules' => ['required', 'string', 'max:255']],
                            ['name' => 'description', 'type' => 'textarea', 'rules' => ['string', 'required']],
                        ])),
                ]),
                Select::make('parent_id')
                    ->label(trans('general.parent_category'))
                    ->options($categories)
                    ->searchable(),

                SpatieMediaLibraryFileUpload::make('image')
                    ->label(trans('general.image'))
                    ->required()
                    ->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')
                    ->label(trans('general.image')),
                Tables\Columns\TextColumn::make('name')
                    ->label(trans('general.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(trans('general.description'))
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label(trans('general.parent_category'))
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
