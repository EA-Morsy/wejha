<?php

return [
    'title' => 'أنواع الحلول',
    'select' => 'اختر نوع الحل',
    'active' => 'نشط',
    'not_active' => 'غير نشط',
    'messages' => [
        'created' => 'تم إضافة نوع الحل بنجاح',
        'updated' => 'تم تعديل نوع الحل بنجاح',
        'deleted' => 'تم حذف نوع الحل بنجاح',
    ],
    'actions' => [
        'add' => 'إضافة نوع حل',
        'edit' => 'تعديل نوع الحل',
        'delete' => 'حذف',
        'show' => 'عرض',
        'cancel' => 'إلغاء',

    ],
    'actions_label' => 'إجراءات',
    'fields' => [
        'name_ar' => 'الاسم (عربي)',
        'name_en' => 'الاسم (إنجليزي)',
        'description_ar' => 'الوصف (عربي)',
        'description_en' => 'الوصف (إنجليزي)',
        'icon' => 'الأيقونة',
        'is_active' => 'نشط',
        'solution' => 'الحل',
        'solution_id' => 'الحل',
        'select_solution' => 'اختر الحل',
    ],
    'validation' => [
        'name_ar_required' => 'الاسم بالعربي مطلوب',
        'name_en_required' => 'الاسم بالإنجليزي مطلوب',
        'icon_image' => 'الأيقونة يجب أن تكون صورة',
        'icon_max' => 'حجم الأيقونة يجب ألا يتجاوز 2 ميجابايت',
    ],
];
