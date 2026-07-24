<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ArrayPaginator
{
    public const PER_PAGE = 10;

    public static function make(array $items, Request $request, int $perPage = self::PER_PAGE): LengthAwarePaginator
    {
        $page = max(1, (int) $request->query('page', 1));
        $total = count($items);
        $results = array_slice($items, ($page - 1) * $perPage, $perPage);

        return new LengthAwarePaginator(
            $results,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
    }
}
