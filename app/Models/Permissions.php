<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Permissions extends Model
{
    public static function getPermission($permission)
    {
        return self::getPermissions()[$permission] ?? '';
    }

    public static function getPermissions()
    {
        return [
            // Users
            'users.create' => __('roles.permissions.users_create'),
            'users.view'   => __('roles.permissions.users_view'),
            'users.delete' => __('roles.permissions.users_delete'),
            'users.edit'   => __('roles.permissions.users_edit'),
        

            // Roles
            'roles.create' => __('roles.permissions.roles_create'),
            'roles.view'   => __('roles.permissions.roles_view'),
            'roles.delete' => __('roles.permissions.roles_delete'),
            'roles.edit'   => __('roles.permissions.roles_edit'),

            // Reports
            'reports.create' => __('roles.permissions.reports_create'),
            'reports.view'   => __('roles.permissions.reports_view'),
            'reports.delete' => __('roles.permissions.reports_delete'),
            'reports.edit'   => __('roles.permissions.reports_edit'),

            // Settings
            'settings.view'   => __('roles.permissions.settings_view'),
            'settings.update' => __('roles.permissions.settings_update'),
            'settings.terms' => __('roles.permissions.settings_terms'),
            'settings.privacy' => __('roles.permissions.settings_privacy'),
            'settings.about' => __('roles.permissions.settings_about'),
            'settings.general' => __('roles.permissions.settings_general'),

            //parteners
            'parteners.create' => __('roles.permissions.parteners_create'),
            'parteners.view'   => __('roles.permissions.parteners_view'),
            'parteners.delete' => __('roles.permissions.parteners_delete'),
            'parteners.edit'   => __('roles.permissions.parteners_edit'),

            // Dashboard
            'dashboard.view' => __('roles.permissions.dashboard_view'),

        ];
    }
}
