<?php

namespace App\Filament\Traits;

use App\Models\Category;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Illuminate\Database\Eloquent\Builder;

trait DynamicFilterTrait
{

       public static function makeDynamicFilter(string $key, array $config)
    {
        $type = $config['type'] ?? 'text';
        $label = $config['label'] ?? ucfirst($key);

        if ($type === 'ternary') {
            return TernaryFilter::make($key)
                ->label($label)
                ->placeholder('Все')
                ->trueLabel($config['trueLabel'] ?? 'Да')
                ->falseLabel($config['falseLabel'] ?? 'Нет')
                ->queries(
                    true: fn (Builder $query) => $query->where($key, true),
                    false: fn (Builder $query) => $query->where($key, false),
                    blank: fn (Builder $query) => $query,
                );
        }

        return Filter::make($key)
            ->label($label)
            ->form([
                TextInput::make('value')
                    ->label($label)
                    ->statePath('value'),
            ])
            ->indicateUsing(function (array $data): ?string {
                return filled($data['value'] ?? null)
                    ? "Содержит: {$data['value']}"
                    : null;
            })
            ->query(function (Builder $query, array $data) use ($key, $config) {
                if (blank($data['value'] ?? null)) {
                    return;
                }

                $operator = $config['operator'] ?? 'like';
                $column = $config['column'] ?? $key;
                $value = $data['value'];

                if ($operator === 'like') {
                    $value = "%{$value}%";
                }

                if (!empty($config['relation'])) {
                    $relation = $config['relation'];
                    $query->whereHas($relation, function ($q) use ($column, $operator, $value) {
                        $q->where($column, $operator, $value);
                    });
                } else {
                    $query->where($column, $operator, $value);
                }
            });
    }




    public static function makeRangeFilter(string $key, array $config)
    {
        
        $column = $config['column'] ?? $key;

        return Filter::make($key)
            ->label($label)
            ->form([
                Grid::make(2)->schema([
                    TextInput::make('from')
                        ->label('От')
                        ->type('number'),

                    TextInput::make('to')
                        ->label('До')
                        ->type('number'),
                ]),
            ])
            ->query(function (Builder $query, array $data) use ($column) {
                return $query
                    ->when(!empty($data['from']), fn ($q) => $q->where($column, '>=', $data['from']))
                    ->when(!empty($data['to']), fn ($q) => $q->where($column, '<=', $data['to']));
            });
    }

    public static function makeDynamicFilters(array $filters): array
    {
        return array_map(function ($key, $config) {
            return match ($config['type'] ?? 'text') {
                'range' => static::makeRangeFilter($key, $config),
                default => static::makeDynamicFilter($key, $config),
            };
        }, array_keys($filters), $filters);
    }
}
