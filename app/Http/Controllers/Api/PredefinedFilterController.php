<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Transformers\SelectlistTransformer;
use App\Models\PredefinedFilter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PredefinedFilterController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $filters = PredefinedFilter::with('permissionGroups')
            ->orderBy('name')
            ->get(['id', 'name', 'created_by', 'is_public']);

        $viewableFilters = $filters->filter(function ($filter) use ($user) {
            if ($filter->created_by === $user->id) {
                return true;
            }

            if ($filter->is_public && $filter->userHasPermission($user, 'view')) {
                return true;
            }

            return false;
        })->values();

        return response()->json($viewableFilters->toArray());
    }

    public function show(Request $request, int $id)
    {
        $user = auth()->user();
        $filter = PredefinedFilter::find($id);

        if (!$filter) {
            return response()->json([
                'message' => trans('admin/predefinedFilters/message.does_not_exist'),
            ], 404);
        }

        if ($filter->created_by === $user->id) {
            return response()->json($filter->toArray());
        }

        if ($filter->is_public && $filter->userHasPermission($user, 'view')) {
            return response()->json($filter->toArray());
        }

        return response()->json([
            'message' => trans('admin/predefinedFilters/message.show.not_allowed'),
        ], 403);
    }

    public function store(Request $request): JsonResponse
    {
        $user = auth()->user();
        $rules = (new PredefinedFilter())->getRules();
        $validated = $request->validate($rules);

        if (!empty($validated['is_public'] ?? false) && ! $user->hasAccess('predefinedFilter.create')) {
            return response()->json([
                'message' => trans('admin/predefinedFilters/message.create.not_allowed'),
            ], 403);
        }

        $predefinedFilter = PredefinedFilter::create([
            'name' => $validated['name'],
            'filter_data' => $validated['filter_data'],
            'created_by' => $user->id,
            'is_public' => $validated['is_public'] ?? 0,
        ]);

        return response()->json([
            'message' => trans('admin/predefinedFilters/message.create.success'),
            'filter_data' => $predefinedFilter,
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $user = auth()->user();
        $filter = PredefinedFilter::find($id);

        if (!$filter) {
            return response()->json([
                'message' => trans('admin/predefinedFilters/message.does_not_exist'),
            ], 404);
        }

        $rules = (new PredefinedFilter())->getRules();
        $validated = $request->validate($rules);

        $currentIsPublic = (bool) $filter->is_public;
        $newIsPublic = (bool) ($validated['is_public'] ?? $filter->is_public);

        if ($filter->created_by === $user->id) {
            if (! $currentIsPublic && $newIsPublic && ! $user->hasAccess('predefinedFilter.create')) {
                return response()->json([
                    'message' => trans('admin/predefinedFilters/message.update.not_allowed_to_change_is_public'),
                ], 403);
            }
        } elseif ($currentIsPublic) {
            if (! $filter->userHasPermission($user, 'update')) {
                return response()->json([
                    'message' => trans('admin/predefinedFilters/message.not_allowed_to_edit'),
                ], 403);
            }
        } else {
            return response()->json([
                'message' => trans('admin/predefinedFilters/message.not_allowed_to_edit'),
            ], 403);
        }

        $filter->name = $validated['name'];
        $filter->filter_data = $validated['filter_data'];
        $filter->is_public = $newIsPublic;
        $filter->save();

        return response()->json([
            'message' => trans('admin/predefinedFilters/message.update.success'),
            'filter_data' => $filter,
        ], 200);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $user = auth()->user();
        $filter = PredefinedFilter::find($id);

        if (!$filter) {
            return response()->json([
                'message' => trans('admin/predefinedFilters/message.does_not_exist'),
            ], 404);
        }

        if ($filter->created_by === $user->id) {
            $filter->delete();
        } elseif ($filter->is_public) {
            if (! $filter->userHasPermission($user, 'destroy')) {
                return response()->json([
                    'message' => trans('admin/predefinedFilters/message.not_allowed_to_delete'),
                ], 403);
            }

            $filter->delete();
        } else {
            return response()->json([
                'message' => trans('admin/predefinedFilters/message.delete.not_allowed_to_delete'),
            ], 403);
        }

        return response()->json([
            'message' => trans('admin/predefinedFilters/message.delete.success'),
        ], 200);
    }

    public function selectlist(Request $request)
    {
        $this->authorize('view.selectlists');
        $user = auth()->user();

        $filters = PredefinedFilter::with('permissionGroups')
            ->orderBy('name')
            ->get(['id', 'name', 'created_by', 'is_public']);

        $viewableFilters = $filters->filter(function ($filter) use ($user) {
            if ($filter->created_by === $user->id) {
                return true;
            }

            if ($filter->is_public && $filter->userHasPermission($user, 'view')) {
                return true;
            }

            return false;
        })->values();

        $predefinedFiltersQuery = PredefinedFilter::select(['id', 'name']);

        if ($request->filled('search')) {
            $predefinedFiltersQuery = $predefinedFiltersQuery->where('name', 'LIKE', '%' . $request->get('search') . '%');
        }

        $predefinedFilters = $predefinedFiltersQuery
            ->whereIn('id', $viewableFilters->pluck('id'))
            ->orderBy('name', 'ASC')
            ->paginate(50);

        foreach ($predefinedFilters as $predefinedFilter) {
            $predefinedFilter->use_text = $predefinedFilter->name;
        }

        return (new SelectlistTransformer)->transformSelectlist($predefinedFilters);
    }
}