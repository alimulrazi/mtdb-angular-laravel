<?php

namespace Api\Auth\Permissions\Traits;

use App\Models\User;
use Api\Auth\Roles\Role;
use Api\Billing\BillingPlan;
use Illuminate\Database\Eloquent\Model;
use Arr;

trait SyncsPermissions
{
    /**
     * @param User|BillingPlan|Role|Model $model
     * @param $permissions
     */
    public function syncPermissions($model, $permissions)
    {
        $permissionIds = collect($permissions)->mapWithKeys(function($permission) {
            $restrictions = Arr::get($permission, 'restrictions', []);
            return [$permission['id'] => [
                'restrictions' => collect($restrictions)
                    ->filter(function($restriction) {
                        return isset($restriction['value']);
                    })->map(function($restriction) {
                        return ['name' => $restriction['name'], 'value' => $restriction['value']];
                    }),
            ]];
        });
        $model->permissions()->sync($permissionIds);
    }
}

