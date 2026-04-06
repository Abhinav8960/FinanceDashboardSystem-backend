<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class FinancialRecordSearch
{
    public static function apply($query, $filters)
    {
        return $query

            ->when($filters['type'] ?? null, function (Builder $q, $type) {
                $q->where('type', $type);
            })

            ->when($filters['category_id'] ?? null, function (Builder $q, $categoryId) {
                $q->where('category_id', $categoryId);
            })

            ->when($filters['from'] ?? null, function (Builder $q, $from) {
                $q->whereDate('date', '>=', $from);
            })

            ->when($filters['to'] ?? null, function (Builder $q, $to) {
                $q->whereDate('date', '<=', $to);
            })

            ->when($filters['search'] ?? null, function (Builder $q, $search) {
                $q->where('description', 'like', "%{$search}%");
            })

            ->orderBy('date', 'desc');
    }
}
