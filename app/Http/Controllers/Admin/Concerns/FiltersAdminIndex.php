<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Shared search/sort boilerplate for Admin *Controller::index() actions.
 */
trait FiltersAdminIndex
{
    /**
     * Apply a case-insensitive LIKE search across plain columns and,
     * optionally, columns on related models - all wrapped in a single
     * closure so the OR chain can never leak into other top-level
     * where() clauses on the query (a bug present in more than one of
     * these controllers before this trait: an orWhere() chained
     * directly after whereHas() applies to the whole query, not just
     * the search).
     *
     * @param array<int, string> $columns Plain columns on the base model.
     * @param array<string, array<int, string>> $relationColumns Map of relation name => columns to search on it.
     */
    protected function applySearch(Builder $query, ?string $search, array $columns = [], array $relationColumns = []): Builder
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search, $columns, $relationColumns) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'like', "%{$search}%");
            }

            foreach ($relationColumns as $relation => $relCols) {
                $q->orWhereHas($relation, function (Builder $rq) use ($search, $relCols) {
                    $rq->where(function (Builder $rq2) use ($search, $relCols) {
                        foreach ($relCols as $col) {
                            $rq2->orWhere($col, 'like', "%{$search}%");
                        }
                    });
                });
            }
        });
    }

    /**
     * Apply sort_by/sort_order from the request, restricted to an
     * allow-list of real, sortable columns so a caller can't force an
     * ORDER BY on an arbitrary/non-existent column.
     */
    protected function applySort(
        Builder $query,
        Request $request,
        array $allowedSorts,
        string $defaultSort,
        string $defaultOrder = 'desc'
    ): Builder {
        $sortBy = $request->get('sort_by', $defaultSort);
        $sortOrder = $request->get('sort_order', $defaultOrder) === 'asc' ? 'asc' : 'desc';

        if (in_array($sortBy, $allowedSorts, true)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        return $query;
    }
}
