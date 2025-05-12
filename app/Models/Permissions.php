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

            //businesses
            'solutions.create' => __('roles.permissions.solutions_create'),
            'solutions.view'   => __('roles.permissions.solutions_view'),
            'solutions.delete' => __('roles.permissions.solutions_delete'),
            'solutions.edit'   => __('roles.permissions.solutions_edit'),
            
            //solution_types
            'solution_types.create' => __('roles.permissions.solution_types_create'),
            'solution_types.view'   => __('roles.permissions.solution_types_view'),
            'solution_types.delete' => __('roles.permissions.solution_types_delete'),
            'solution_types.edit'   => __('roles.permissions.solution_types_edit'),

            //industries
            'industries.create' => __('roles.permissions.industries_create'),
            'industries.view'   => __('roles.permissions.industries_view'),
            'industries.delete' => __('roles.permissions.industries_delete'),
            'industries.edit'   => __('roles.permissions.industries_edit'),

            //articles
            'articles.create' => __('roles.permissions.articles_create'),
            'articles.view'   => __('roles.permissions.articles_view'),
            'articles.delete' => __('roles.permissions.articles_delete'),
            'articles.edit'   => __('roles.permissions.articles_edit'),

            //pages
            'pages.create' => __('roles.permissions.pages_create'),
            'pages.view'   => __('roles.permissions.pages_view'),
            'pages.delete' => __('roles.permissions.pages_delete'),
            'pages.edit'   => __('roles.permissions.pages_edit'),

            //sections
            'sections.create' => __('roles.permissions.sections_create'),
            'sections.view'   => __('roles.permissions.sections_view'),
            'sections.delete' => __('roles.permissions.sections_delete'),
            'sections.edit'   => __('roles.permissions.sections_edit'),

            //products
            'products.create' => __('roles.permissions.products_create'),
            'products.view'   => __('roles.permissions.products_view'),
            'products.delete' => __('roles.permissions.products_delete'),
            'products.edit'   => __('roles.permissions.products_edit'),
          
            
            // Dashboard
            'dashboard.view' => __('roles.permissions.dashboard_view'),

        ];
    }
}
