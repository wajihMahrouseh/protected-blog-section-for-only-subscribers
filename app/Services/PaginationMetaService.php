<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;

class PaginationMetaService
{
    public function generatePaginationMeta(LengthAwarePaginator $paginator = null)
    {
        if ($paginator) {
            return [
                "current_page" => $paginator->currentPage(),
                "last_page" => $paginator->lastPage(),
                "per_page" => $paginator->perPage(),
                "path" => $paginator->path(),
                "fragment" => $paginator->fragment(),
                "first_page_url" => $paginator->url(1),
                "last_page_url" => $paginator->url($paginator->lastPage()),
                "next_page_url" => $paginator->nextPageUrl(),
                "prev_page_url" => $paginator->previousPageUrl(),
                "from" => $paginator->firstItem(),
                "to" => $paginator->lastItem(),
                "total" => $paginator->total(),
                'links' => $paginator->render(),
            ];
        }

        return null;
    }
}
