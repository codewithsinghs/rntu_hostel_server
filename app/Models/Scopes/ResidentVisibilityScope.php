<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ResidentVisibilityScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    // public function apply(Builder $builder, Model $model): void
    // {
    //     //
    // }

    // public function apply(Builder $builder, Model $model)
    // {
    //     $user = auth()->user();

    //     /* ---------------------------------
    //      | No Auth → No Data
    //      |---------------------------------*/
    //     if (! $user) {
    //         $builder->whereRaw('1 = 0');
    //         return;
    //     }

    //     /* ---------------------------------
    //      | Normalize roles (case-insensitive)
    //      |---------------------------------*/
    //     $roles = collect($user->getRoleNames())
    //         ->map(fn($r) => strtoupper(trim($r)))
    //         ->toArray();

    //     /* ---------------------------------
    //      | Full access roles
    //      |---------------------------------*/
    //     if (array_intersect($roles, [
    //         'SUPER_ADMIN',
    //         'SYSTEM_ADMIN',
    //         'ROOT',
    //     ])) {
    //         return; // No restriction
    //     }

    //     /* ---------------------------------
    //      | No university → No access
    //      |---------------------------------*/
    //     if (empty($user->university_id)) {
    //         $builder->whereRaw('1 = 0');
    //         return;
    //     }

    //     /* ---------------------------------
    //      | Default university-level access
    //      |---------------------------------*/
    //     $builder->whereHas('user', function ($q) use ($user) {
    //         $q->where('university_id', $user->university_id);
    //     });
    // }

    // public function apply(Builder $builder, Model $model)
    // {
    //     $user = auth()->user();

    //     /* ===============================
    //      | NO USER → NO DATA
    //      =============================== */
    //     if (! $user) {
    //         $builder->whereRaw('1 = 0');
    //         return;
    //     }

    //     $role = $user->normalizedRole();
    //     $buildings = $user->normalizedBuildingIds();
    //     $universityId = $user->university_id ?? null;

    //     /* ===============================
    //      | FULL ACCESS ROLES
    //      =============================== */
    //     if (in_array($role, ['SUPER_ADMIN', 'SYSTEM_ADMIN'], true)) {
    //         return;
    //     }

    //     /* ===============================
    //      | NO UNIVERSITY → NO DATA
    //      =============================== */
    //     if (! $universityId) {
    //         $builder->whereRaw('1 = 0');
    //         return;
    //     }

    //     /* ===============================
    //      | WARDEN → UNIVERSITY + BUILDING
    //      =============================== */
    //     if ($role === 'WARDEN') {

    //         if (empty($buildings)) {
    //             $builder->whereRaw('1 = 0');
    //             return;
    //         }

    //         $builder
    //             ->whereIn('building_id', $buildings)
    //             ->whereHas('user', function ($q) use ($universityId) {
    //                 $q->where('university_id', $universityId);
    //             });

    //         return;
    //     }

    //     /* ===============================
    //      | DEFAULT (ADMIN / STAFF)
    //      | UNIVERSITY ONLY
    //      =============================== */
    //     $builder->whereHas('user', function ($q) use ($universityId) {
    //         $q->where('university_id', $universityId);
    //     });
    // }



    // public function apply(Builder $builder, Model $model)
    // {
    //     $user = auth()->user();

    //     /* ---------------------------------
    //  | No Auth → No Data
    //  |---------------------------------*/
    //     if (!$user) {
    //         $builder->whereRaw('1 = 0');
    //         return;
    //     }

    //     /* ---------------------------------
    //  | Normalize roles (case-insensitive)
    //  |---------------------------------*/
    //     $roles = collect($user->getRoleNames())
    //         ->map(fn($r) => strtoupper(trim($r)))
    //         ->toArray();

    //     /* ---------------------------------
    //  | Full access roles
    //  |---------------------------------*/
    //     if (array_intersect($roles, [
    //         'SUPER_ADMIN', 'admin',
    //         'SYSTEM_ADMIN',
    //         'ROOT',
    //     ])) {
    //         return; // No restriction
    //     }

    //     /* ---------------------------------
    //  | No university → No access
    //  |---------------------------------*/
    //     if (empty($user->university_id)) {
    //         $builder->whereRaw('1 = 0');
    //         return;
    //     }

    //     /* ---------------------------------
    //  | Building access (for admin/warden)
    //  |---------------------------------*/
    //     $buildingIds = [];

    //     if (!empty($user->building_id)) {
    //         // If building_id is JSON array or comma separated string
    //         $buildingIds = is_array($user->building_id)
    //             ? $user->building_id
    //             : explode(',', $user->building_id);

    //         $buildingIds = array_filter(array_map('trim', $buildingIds));
    //     }

    //     /* ---------------------------------
    //  | If no buildings assigned → no access
    //  |---------------------------------*/
    //     if (empty($buildingIds)) {
    //         $builder->whereRaw('1 = 0');
    //         return;
    //     }

    //     /* ---------------------------------
    //  | Apply building filter through user relation
    //  |---------------------------------*/
    //     $builder->whereHas('user', function ($q) use ($buildingIds) {
    //         $q->whereIn('building_id', $buildingIds);
    //     });
    // }


    public function apply(Builder $builder, Model $model)
    {
        $user = auth()->user();

        if (!$user) {
            $builder->whereRaw('1 = 0');
            return;
        }

        $roles = collect($user->getRoleNames())
            ->map(fn($r) => strtoupper(trim($r)))
            ->toArray();

        // FULL ACCESS ROLES
        if (array_intersect($roles, ['SUPER_ADMIN', 'SYSTEM_ADMIN', 'ROOT'])) {
            return; // no restrictions
        }

        // ADMIN (GLOBAL ACCESS) - even if building_id null
        if (in_array('ADMIN', $roles)) {
            if (empty($user->building_id)) {
                return; // full access
            }
        }

        // WARDEN - MUST have building
        if (in_array('WARDEN', $roles)) {
            $buildingIds = $user->building_id;

            if (is_string($buildingIds)) {
                $buildingIds = json_decode($buildingIds, true) ?: explode(',', $buildingIds);
            }

            $buildingIds = array_filter((array)$buildingIds);

            if (empty($buildingIds)) {
                $builder->whereRaw('1 = 0');
                return;
            }

            $builder->whereHas('resident.user', function ($q) use ($buildingIds) {
                $q->whereIn('building_id', $buildingIds);
            });

            return;
        }

        // OTHER ROLES - NO ACCESS
        $builder->whereRaw('1 = 0');
    }
}
