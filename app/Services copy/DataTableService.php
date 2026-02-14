<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class DataTableService
{
    public static function fromQuery(
        Request $request,
        Builder $query,
        callable $mapRow
    ) {
        $draw   = (int) $request->input('draw');
        $start  = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        $search = $request->input('search.value');

        /* -----------------------------
         | Total Records
         |-----------------------------*/
        $recordsTotal = (clone $query)->count();

        /* -----------------------------
         | Search
         |-----------------------------*/
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('number', 'like', "%{$search}%")
                    ->orWhere('scholar_no', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = (clone $query)->count();

        /* -----------------------------
         | Pagination
         |-----------------------------*/
        $results = $query
            ->skip($start)
            ->take($length)
            ->get();

        /* -----------------------------
         | Map rows (custom per table)
         |-----------------------------*/
        $data = $results->map($mapRow)->values();

        return [
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ];
    }
}
