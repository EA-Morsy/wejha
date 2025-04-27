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
            // Cities
            'cities.create' => __('roles.permissions.cities_create'),
            'cities.view'   => __('roles.permissions.cities_view'),
            'cities.delete' => __('roles.permissions.cities_delete'),
            'cities.edit'   => __('roles.permissions.cities_edit'),

            //companies
            'companies.create' => __('roles.permissions.companies_create'),
            'companies.view'   => __('roles.permissions.companies_view'),
            'companies.delete' => __('roles.permissions.companies_delete'),
            'companies.edit'   => __('roles.permissions.companies_edit'),

            //categories
            'categories.create' => __('roles.permissions.categories_create'),
            'categories.view'   => __('roles.permissions.categories_view'),
            'categories.delete' => __('roles.permissions.categories_delete'),
            'categories.edit'   => __('roles.permissions.categories_edit'),

            // coupons
            'coupons.create' => __('roles.permissions.coupons_create'),
            'coupons.view'   => __('roles.permissions.coupons_view'),
            'coupons.delete' => __('roles.permissions.coupons_delete'),
            'coupons.edit'   => __('roles.permissions.coupons_edit'),

            // Users
            'users.create' => __('roles.permissions.users_create'),
            'users.view'   => __('roles.permissions.users_view'),
            'users.delete' => __('roles.permissions.users_delete'),
            'users.edit'   => __('roles.permissions.users_edit'),
            'users.show'   => __('roles.permissions.users_show'),

            // Roles
            'roles.create' => __('roles.permissions.roles_create'),
            'roles.view'   => __('roles.permissions.roles_view'),
            'roles.delete' => __('roles.permissions.roles_delete'),
            'roles.edit'   => __('roles.permissions.roles_edit'),

            // Permissions
            'permissions.create' => __('roles.permissions.permissions_create'),
            'permissions.view'   => __('roles.permissions.permissions_view'),
            'permissions.delete' => __('roles.permissions.permissions_delete'),
            'permissions.edit'   => __('roles.permissions.permissions_edit'),

            // Events
            'events.create' => __('roles.permissions.events_create'),
            'events.view'   => __('roles.permissions.events_view'),
            'events.delete' => __('roles.permissions.events_delete'),
            'events.edit'   => __('roles.permissions.events_edit'),

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

            // Notifications
            'notifications.create' => __('roles.permissions.notifications_create'),
            'notifications.view'   => __('roles.permissions.notifications_view'),
            'notifications.delete' => __('roles.permissions.notifications_delete'),
            'notifications.edit'   => __('roles.permissions.notifications_edit'),

            // sliders
            'sliders.create' => __('roles.permissions.sliders_create'),
            'sliders.view'   => __('roles.permissions.sliders_view'),
            'sliders.delete' => __('roles.permissions.sliders_delete'),
            'sliders.edit'   => __('roles.permissions.sliders_edit'),

            // Dashboard
            'dashboard.view' => __('roles.permissions.dashboard_view'),

        ];
    }
}
