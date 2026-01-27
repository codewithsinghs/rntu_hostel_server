<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class UniversityScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $user = auth()->user();

        if (
            $user &&
            $user->university_id &&
            ! $user->is_super_admin &&
            schema_has_column($model->getTable(), 'university_id')
        ) {
            $builder->where(
                $model->getTable() . '.university_id',
                $user->university_id
            );
        }
    }
}
