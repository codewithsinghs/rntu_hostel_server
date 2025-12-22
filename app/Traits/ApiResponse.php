<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Success response
     */
    protected function success($data = [], string $message = '', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Error response
     */
    protected function error(string $message = '', int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'status' => 'error',
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * DataTables-style response
     */
    protected function dataTableResponse($query, $request, $transform = null): JsonResponse
    {
        $draw = $request->input('draw', 1);
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $search = $request->input('search.value', '');

        $totalRecords = $query->count();

        if ($search) {
            $query = $query->where(function ($q) use ($search) {
                foreach ($q->getModel()->getFillable() as $column) {
                    $q->orWhere($column, 'like', "%{$search}%");
                }
            });
        }

        $filteredRecords = $query->count();

        if ($request->input('order')) {
            $orderColumn = $request->input('columns')[$request->input('order.0.column')]['data'];
            $orderDir = $request->input('order.0.dir');
            $query->orderBy($orderColumn, $orderDir);
        }

        $data = $query->skip($start)->take($length)->get();

        if ($transform && is_callable($transform)) {
            $data = $data->map($transform);
        }

        return response()->json([
            'draw' => (int) $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
    }
}
