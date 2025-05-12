<?php

return [
    'title' => 'Solution Types',
    'select' => 'Select Solution Type',
    'active' => 'Active',
    'not_active' => 'Not Active',
    'messages' => [
        'created' => 'Solution type created successfully',
        'updated' => 'Solution type updated successfully',
        'deleted' => 'Solution type deleted successfully',
    ],
    'actions' => [
        'add' => 'Add Solution Type',
        'edit' => 'Edit Solution Type',
        'delete' => 'Delete',
        'show' => 'View',
        'cancel' => 'Cancel',
    ],
    'actions_label' => 'Actions',
    'fields' => [
        'name_ar' => 'Name (Arabic)',
        'name_en' => 'Name (English)',
        'description_ar' => 'Description (Arabic)',
        'description_en' => 'Description (English)',
        'icon' => 'Icon',
        'is_active' => 'Active',
        'solution' => 'Solution',
        'solution_id' => 'Solution',
        'select_solution' => 'Select Solution',
    ],
    'validation' => [
        'name_ar_required' => 'Arabic name is required',
        'name_en_required' => 'English name is required',
        'icon_image' => 'Icon must be an image',
        'icon_max' => 'Icon size must not exceed 2MB',
    ],
];
