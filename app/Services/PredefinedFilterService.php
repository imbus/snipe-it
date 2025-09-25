<?php

namespace App\Services;

use App\Models\PredefinedFilter;
use App\Services\PredefinedFilterPermissionService;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Throwable;

class PredefinedFilterService
{
    private PredefinedFilterPermissionService $predefinedFilterPermissionService;

    public function __construct(PredefinedFilterPermissionService $predefinedFilterPermissionService)
    {
        $this->predefinedFilterPermissionService = $predefinedFilterPermissionService;
    }

    public function getAllViewableFilters(): Collection
    {
        $user = Auth::user();

        return PredefinedFilter::with('permissionGroups')
            ->orderBy('name')
            ->get(['id', 'name', 'created_by', 'is_public'])
            ->filter(function ($filter) use ($user) {
                if ($filter->created_by == $user->id) {
                    return true;
                }

                if ($filter->is_public && $filter->userHasPermission($user, 'view')) {
                    return true;
                }

                return false;
            })->values();
    }

    public function getFilterById(int $id, bool $include_predefined_filter_groups = true)
    {
        $predefinedFilter = PredefinedFilter::find($id);
        if($include_predefined_filter_groups) {
            $permissions = $this->predefinedFilterPermissionService->getPermissionsByPredefinedFilterId($id);
            $predefinedFilter['permissions'] = $permissions;
        }
        return $predefinedFilter;
    }

    public function canUserViewFilter($filter): bool
    {
        $user = Auth::user();

        return $filter->created_by == $user->id ||
               ($filter->is_public && $filter->userHasPermission($user, 'view'));
    }

    public function createFilter(array $validated): PredefinedFilter
    {
        $filterCreateResponse = PredefinedFilter::create([
            'name' => $validated['name'],
            'filter_data' => $validated['filter_data'],
            'created_by' => Auth::id(),
            'is_public' => $validated['is_public'] ?? 0,
        ]);

        if (array_key_exists('permissions', $validated)) {
            foreach ($validated['permissions'] as $permission) {
                $permission['predefined_filter_id'] = $filterCreateResponse->id;
                $this->predefinedFilterPermissionService->store($permission);
            }
        }

        return $filterCreateResponse;
    }

    public function updateFilter(PredefinedFilter $filter, array $validated): PredefinedFilter
    {
        $filter->fill([
            'name' => $validated['name'],
            'filter_data' => $validated['filter_data'],
            'is_public' => $validated['is_public'] ?? $filter->is_public,
        ]);
        $filter->save();

        if (array_key_exists('permissions', $validated)) {
<<<<<<< HEAD
            $currentlySetPermissions = $this->predefinedFilterPermissionService->getPermissionsById($filter->id);
            $newPermissions = $validated['permissions'];
            $permissionDiff = $this->syncPermissions($currentlySetPermissions->toArray(), $newPermissions);
=======
            $currently_set_permssions = $this->predefinedFilterPermissionService->getPermissionsByPredefinedFilterId($filter->id);
            $new_permissions = $validated['permissions'];
            $permission_diff = $this->syncPermissions($currently_set_permssions->toArray(), $new_permissions);
            //dump($permission_diff);
>>>>>>> 5c0ea72d90 (Fixed that the same group can be assigned multiple times to the same filter)

            try {
                DB::transaction(function () use ($permissionDiff, $filter) {
                    if (!empty($permissionDiff['to_delete'])) {
                        foreach ($permissionDiff['to_delete'] as $permission) {
                            $this->predefinedFilterPermissionService->deletePermissionByFilterId($permission['predefined_filter_id']);
                        }
                    }

                    if (!empty($permissionDiff['to_add'])) {
                        foreach ($permissionDiff['to_add'] as $permission) {
                            $permission['predefined_filter_id'] = $filter->id;
                            $this->predefinedFilterPermissionService->store($permission);
                        }
                    }
                });
            } catch (Throwable $e) {
                throw new Exception($e->getMessage());
            }
        }

        return $filter;
    }

    public function deleteFilter(PredefinedFilter $filter): void
    {
        $filter->delete();
    }

    public function selectList(Request $request): LengthAwarePaginator
    {
        $user = Auth::user();

        $filters = PredefinedFilter::with('permissionGroups')
            ->orderBy('name')
            ->get(['id', 'name', 'created_by', 'is_public']);

        $viewableFilters = $filters->filter(function ($filter) use ($user) {
            if ($filter->created_by == $user->id) {
                return true;
            }

            if ($filter->is_public && $filter->userHasPermission($user, 'view')) {
                return true;
            }

            return false;
        })->pluck('id');

        $query = PredefinedFilter::select(['id', 'name'])
            ->whereIn('id', $viewableFilters);

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->get('search') . '%');
        }

        $paginated = $query->orderBy('name')->paginate(50);

        foreach ($paginated as $item) {
            $item->use_text = $item->name;
        }

        return $paginated;
    }

    private function syncPermissions($currentPermissions, $newPermissions): array
    {
        $toAdd = array_udiff(
            $newPermissions,
            $currentPermissions,
            function ($obj_a, $obj_b) {
                return $obj_a['permission_group_id'] !== $obj_b['permission_group_id'];
            }
        );

        $toDelete = array_udiff(
            $currentPermissions,
            $newPermissions,
            function ($obj_a, $obj_b) {
                return $obj_a['permission_group_id'] !== $obj_b['permission_group_id'];
            }
        );

        return [
            'to_add' => $toAdd,
            'to_delete' => $toDelete,
        ];
    }
}