<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\PredefinedFiltersTransformer;
use App\Http\Transformers\SelectlistTransformer;
use App\Models\PredefinedFilter;
use App\Services\PredefinedFilterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PredefinedFilterController extends Controller
{
    protected PredefinedFilterService $service;

    public function __construct(PredefinedFilterService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $filters = $this->service->getAllViewableFilters();

        return response()->json((new PredefinedFiltersTransformer)->transformPredefinedFilters($filters, $filters->count()));
    }

    public function show(int $id): JsonResponse
    {
        $filter = $this->service->getFilterById($id);

        if (! $filter) {
        if (empty($validated['filter_data'])) {
            return response()->json([
                'message' => trans('admin/predefinedFilters/message.update.filterData_required'),
            ], 400);
        }
}
