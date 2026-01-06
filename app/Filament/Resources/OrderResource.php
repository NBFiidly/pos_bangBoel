<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\OrderDetailRelationManager;
use App\Models\Customer;
use App\Models\Product;
use Filament\Forms\Components\Placeholder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Kasir';
    protected static ?string $label = 'Pesanan';
    protected static ?string $pluralLabel = 'Pesanan';

    public static function getNavigationUrl(): string
    {
        return static::getUrl('create');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DateTimePicker::make('date')
                    ->default(now())
                    ->disabled()
                    ->hiddenLabel()
                    ->dehydrated()
                    ->prefix('Tanggal Pesanan: '),
                Section::make()
                    ->description('Informasi Pelanggan')
                    ->schema([
                        Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->required()
                            ->label('Nama')
                            ->reactive()
                            ->afterStateUpdated( function ($state, Set $set, Get $get) {
                                $customer = Customer::find($state);
                                $phone = $customer->phone ?? '';
                                $address = $customer->address ?? '';
                                $set('No HP', $phone);
                                $set('Alamat', $address);
                            }),
                            Placeholder::make('No Hp')
                                ->content(fn (Get $get) => Customer::find($get('customer_id'))?->phone ?? ''),
                            Placeholder::make('Alamat')
                                ->content(fn (Get $get) => Customer::find($get('customer_id'))?->address ?? ''),
                    ])->columns(3),

                    Section::make()
                    ->description('Detail Pesanan')
                    ->schema([
                        Repeater::make('orderdetails')
                            ->relationship()
                            ->label('Detail Pesanan')
                            ->addActionLabel('Tambah Pesanan')
                            ->schema([
                                Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->label('Nama Produk')
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $product = \App\Models\Product::find($state);
                                        $price = $product->price ?? 0;
                                        $set('price', $price);

                                        $qty = $get('quantity') ?? 1;
                                        $set('quantity', $qty);

                                        $subtotal = $price * $qty;
                                        $set('subtotal', $subtotal);

                                        $items = $get('../../orderdetails') ?? [];
                                        $total = array_sum(array_column($items, 'subtotal'));
                                        $set('../../total_price', $total);
                                    }),
                                TextInput::make('price')
                                    ->label('Harga')
                                    ->readOnly()
                                    ->numeric()
                                    ->disabled()
                                    ->formatStateUsing(fn ($state, Get $get) => $state ?? Product::find($get('product_id'))->price ?? 0),
                                TextInput::make('quantity')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->default(1)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $price = $get('price') ?? 0;
                                        $set('subtotal', $price * $state);

                                        $items = $get('../../orderdetails') ?? [];
                                        $total = collect($items)->sum(fn($item) => $item['subtotal'] ?? 0);
                                        $set('../../total_price', $total);
                                    }),
                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(),
                            ])->columns(4),
                    ]),

                Forms\Components\TextInput::make('total_price')
                    ->label('Harga Total')
                    ->required()
                    ->disabled()
                    ->dehydrated()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->numeric()
                    ->label('Nama')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->numeric()
                    ->money('IDR')
                    ->label('Total Harga')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->label('Tanggal Order')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            OrderDetailRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
