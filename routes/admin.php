<?php

use Illuminate\Support\Facades\Route;

Route::middleware('throttle:60,1')->group(function () {
    Route::get('admin/login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('admin.login');
    Route::post('admin/login', [App\Http\Controllers\Admin\AuthController::class, 'postLogin'])->name('admin.postLogin');
    Route::post('admin/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('admin.logout');
    Route::group(['middleware' => ['language']], function () {

        Route::group(
            ['middleware' => 'authenticate.admin', 'as' => 'admin.'],
            function () {
                Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'index']);
            }
        );
        Route::group(['middleware' => 'authenticate.admin', 'as' => 'admin.', 'prefix' => 'admin'], function () {
            Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('home');

            Route::patch('/fcm-token', [App\Http\Controllers\Admin\AdminController::class, 'updateToken'])->name('fcmToken');
            Route::post('/send-notification', [App\Http\Controllers\Admin\AdminController::class,'notification'])->name('notification');

            Route::get('products-rates', [App\Http\Controllers\Admin\RateController::class, 'product'])->name('rates.products')->middleware('permission:rates.products');
            Route::get('productrates/list', [App\Http\Controllers\Admin\RateController::class, 'listProducts'])->name('rates.listProducts')->middleware('permission:rates.products');

            Route::get('company-rates', [App\Http\Controllers\Admin\RateController::class, 'company'])->name('rates.companies')->middleware('permission:rates.companies');
            Route::get('companyrates/list', [App\Http\Controllers\Admin\RateController::class, 'listCompanies'])->name('rates.listCompanies')->middleware('permission:rates.companies');

            Route::get('sliders/select', [App\Http\Controllers\Admin\SliderController::class, 'select'])->name('sliders.select');
            Route::delete('sliders/bulk', [App\Http\Controllers\Admin\SliderController::class, 'deleteBulk'])->name('sliders.deleteBulk')->middleware('permission:sliders.delete');
            Route::get('sliders/list', [App\Http\Controllers\Admin\SliderController::class, 'list'])->name('sliders.list')->middleware('permission:sliders.view');
            Route::post('sliders', [App\Http\Controllers\Admin\SliderController::class, 'store'])->name('sliders.store')->middleware('permission:sliders.create');
            Route::delete('sliders/{id}', [App\Http\Controllers\Admin\SliderController::class, 'destroy'])->name('sliders.destroy')->middleware('permission:sliders.delete');
            Route::get('sliders', [App\Http\Controllers\Admin\SliderController::class, 'index'])->name('sliders.index')->middleware('permission:sliders.view');
            Route::get('sliders/create', [App\Http\Controllers\Admin\SliderController::class, 'create'])->name('sliders.create')->middleware('permission:sliders.create');
            Route::match(['PUT', 'PATCH'], 'sliders/{id}', [App\Http\Controllers\Admin\SliderController::class, 'update'])->name('sliders.update')->middleware('permission:sliders.edit');
            Route::get('sliders/{id}/edit', [App\Http\Controllers\Admin\SliderController::class, 'edit'])->name('sliders.edit')->middleware('permission:sliders.edit');

            Route::get('notifications/select', [App\Http\Controllers\Admin\NotificationController::class, 'select'])->name('notifications.select');
            Route::delete('notifications/bulk', [App\Http\Controllers\Admin\NotificationController::class, 'deleteBulk'])->name('notifications.deleteBulk')->middleware('permission:notifications.delete');
            Route::get('notifications/list', [App\Http\Controllers\Admin\NotificationController::class, 'list'])->name('notifications.list')->middleware('permission:notifications.view');
            Route::post('notifications', [App\Http\Controllers\Admin\NotificationController::class, 'store'])->name('notifications.store')->middleware('permission:notifications.create');
            Route::delete('notifications/{id}', [App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('notifications.destroy')->middleware('permission:notifications.delete');
            Route::get('notifications', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index')->middleware('permission:notifications.view');
            Route::get('notifications/create', [App\Http\Controllers\Admin\NotificationController::class, 'create'])->name('notifications.create')->middleware('permission:notifications.create');
            Route::match(['PUT', 'PATCH'], 'notifications/{id}', [App\Http\Controllers\Admin\NotificationController::class, 'update'])->name('notifications.update')->middleware('permission:notifications.edit');
            Route::get('notifications/{id}/edit', [App\Http\Controllers\Admin\NotificationController::class, 'edit'])->name('notifications.edit')->middleware('permission:notifications.edit');

            Route::get('companies/select', [App\Http\Controllers\Admin\CompanyController::class, 'select'])->name('companies.select');
            Route::delete('companies/bulk', [App\Http\Controllers\Admin\CompanyController::class, 'deleteBulk'])->name('companies.deleteBulk')->middleware('permission:companies.delete');
            Route::get('companies/list', [App\Http\Controllers\Admin\CompanyController::class, 'list'])->name('companies.list')->middleware('permission:companies.view');
            Route::post('companies', [App\Http\Controllers\Admin\CompanyController::class, 'store'])->name('companies.store')->middleware('permission:companies.create');
            Route::delete('companies/{id}', [App\Http\Controllers\Admin\CompanyController::class, 'destroy'])->name('companies.destroy')->middleware('permission:companies.delete');
            Route::get('companies', [App\Http\Controllers\Admin\CompanyController::class, 'index'])->name('companies.index')->middleware('permission:companies.view');
            Route::get('companies/create', [App\Http\Controllers\Admin\CompanyController::class, 'create'])->name('companies.create')->middleware('permission:companies.create');
            Route::match(['PUT', 'PATCH'], 'companies/{id}', [App\Http\Controllers\Admin\CompanyController::class, 'update'])->name('companies.update')->middleware('permission:companies.edit');
            Route::get('companies/{id}/edit', [App\Http\Controllers\Admin\CompanyController::class, 'edit'])->name('companies.edit')->middleware('permission:companies.edit');

            Route::get('categories/select', [App\Http\Controllers\Admin\CategoryController::class, 'select'])->name('categories.select');
            Route::delete('categories/bulk', [App\Http\Controllers\Admin\CategoryController::class, 'deleteBulk'])->name('categories.deleteBulk')->middleware('permission:categories.delete');
            Route::get('categories/list', [App\Http\Controllers\Admin\CategoryController::class, 'list'])->name('categories.list')->middleware('permission:categories.view');
            Route::post('categories', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store')->middleware('permission:categories.create');
            Route::delete('categories/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy')->middleware('permission:categories.delete');
            Route::get('categories', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index')->middleware('permission:categories.view');
            Route::get('categories/create', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categories.create')->middleware('permission:categories.create');
            Route::match(['PUT', 'PATCH'], 'categories/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update')->middleware('permission:categories.edit');
            Route::get('categories/{id}/edit', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categories.edit')->middleware('permission:categories.edit');

            Route::get('cities/select', [App\Http\Controllers\Admin\CityController::class, 'select'])->name('cities.select');
            Route::delete('cities/bulk', [App\Http\Controllers\Admin\CityController::class, 'deleteBulk'])->name('cities.deleteBulk')->middleware('permission:cities.delete');
            Route::get('cities/list', [App\Http\Controllers\Admin\CityController::class, 'list'])->name('cities.list')->middleware('permission:cities.view');
            Route::post('cities', [App\Http\Controllers\Admin\CityController::class, 'store'])->name('cities.store')->middleware('permission:cities.create');
            Route::delete('cities/{id}', [App\Http\Controllers\Admin\CityController::class, 'destroy'])->name('cities.destroy')->middleware('permission:cities.delete');
            Route::get('cities', [App\Http\Controllers\Admin\CityController::class, 'index'])->name('cities.index')->middleware('permission:cities.view');
            Route::get('cities/create', [App\Http\Controllers\Admin\CityController::class, 'create'])->name('cities.create')->middleware('permission:cities.create');
            Route::match(['PUT', 'PATCH'], 'cities/{id}', [App\Http\Controllers\Admin\CityController::class, 'update'])->name('cities.update')->middleware('permission:cities.edit');
            Route::get('cities/{id}/edit', [App\Http\Controllers\Admin\CityController::class, 'edit'])->name('cities.edit')->middleware('permission:cities.edit');

            Route::get('coupons/select', [App\Http\Controllers\Admin\CouponController::class, 'select'])->name('coupons.select');
            Route::delete('coupons/bulk', [App\Http\Controllers\Admin\CouponController::class, 'deleteBulk'])->name('coupons.deleteBulk')->middleware('permission:coupons.delete');
            Route::get('coupons/list', [App\Http\Controllers\Admin\CouponController::class, 'list'])->name('coupons.list')->middleware('permission:coupons.view');
            Route::post('coupons', [App\Http\Controllers\Admin\CouponController::class, 'store'])->name('coupons.store')->middleware('permission:coupons.create');
            Route::delete('coupons/{id}', [App\Http\Controllers\Admin\CouponController::class, 'destroy'])->name('coupons.destroy')->middleware('permission:coupons.delete');
            Route::get('coupons', [App\Http\Controllers\Admin\CouponController::class, 'index'])->name('coupons.index')->middleware('permission:coupons.view');
            Route::get('coupons/create', [App\Http\Controllers\Admin\CouponController::class, 'create'])->name('coupons.create')->middleware('permission:coupons.create');
            Route::match(['PUT', 'PATCH'], 'coupons/{id}', [App\Http\Controllers\Admin\CouponController::class, 'update'])->name('coupons.update')->middleware('permission:coupons.edit');
            Route::get('coupons/{id}/edit', [App\Http\Controllers\Admin\CouponController::class, 'edit'])->name('coupons.edit')->middleware('permission:coupons.edit');

            Route::get('contacts/select', [App\Http\Controllers\Admin\ContactController::class, 'select'])->name('contacts.select');
            Route::delete('contacts/bulk', [App\Http\Controllers\Admin\ContactController::class, 'deleteBulk'])->name('contacts.deleteBulk')->middleware('permission:contacts.delete');
            Route::get('contacts/list', [App\Http\Controllers\Admin\ContactController::class, 'list'])->name('contacts.list')->middleware('permission:contacts.view');
            Route::delete('contacts/{id}', [App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('contacts.destroy')->middleware('permission:contacts.delete');
            Route::get('contacts', [App\Http\Controllers\Admin\ContactController::class, 'index'])->name('contacts.index')->middleware('permission:contacts.view');


            Route::get('users/select', [App\Http\Controllers\Admin\UserController::class, 'select'])->name('users.select');
            Route::get('users/list', [App\Http\Controllers\Admin\UserController::class, 'list'])->name('users.list')->middleware('permission:users.view');
            Route::post('users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store')->middleware('permission:users.create');
            Route::post('restore/{id}', [App\Http\Controllers\Admin\UserController::class, 'restore'])->name('users.restore')->middleware('permission:users.create');
            Route::delete('users/{id}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:users.delete');
            Route::get('users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index')->middleware('permission:users.view');
            Route::get('users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create')->middleware('permission:users.create');
            Route::match(['PUT', 'PATCH'], 'users/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update')->middleware('permission:users.edit');
            Route::get('users/{id}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit')->middleware('permission:users.edit');
            Route::get('users/show/{id}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show')->middleware('permission:users.show');

            Route::get('roles/select', [App\Http\Controllers\Admin\RoleController::class, 'select'])->name('roles.select');
            Route::get('roles/list', [App\Http\Controllers\Admin\RoleController::class, 'list'])->name('roles.list')->middleware('permission:roles.view');
            Route::post('roles', [App\Http\Controllers\Admin\RoleController::class, 'store'])->name('roles.store')->middleware('permission:roles.create');
            Route::delete('roles/{id}', [App\Http\Controllers\Admin\RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:roles.delete');
            Route::get('roles', [App\Http\Controllers\Admin\RoleController::class, 'index'])->name('roles.index')->middleware('permission:roles.view');
            Route::get('roles/create', [App\Http\Controllers\Admin\RoleController::class, 'create'])->name('roles.create')->middleware('permission:roles.create');
            Route::match(['PUT', 'PATCH'], 'roles/{id}', [App\Http\Controllers\Admin\RoleController::class, 'update'])->name('roles.update')->middleware('permission:roles.edit');
            Route::get('roles/{id}/edit', [App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:roles.edit');


            Route::get('settings/general', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index')->middleware('permission:settings.general');
            Route::get('settings/about', [App\Http\Controllers\Admin\SettingController::class, 'about'])->name('settings.about')->middleware('permission:settings.about');
            Route::get('settings/privacy', [App\Http\Controllers\Admin\SettingController::class, 'privacy'])->name('settings.privacy')->middleware('permission:settings.privacy');
            Route::get('settings/terms', [App\Http\Controllers\Admin\SettingController::class, 'terms'])->name('settings.terms')->middleware('permission:settings.terms');
            Route::post('settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update')->middleware('permission:settings.edit');


            Route::get('profile', [App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile.index');
            Route::post('update-profile', [App\Http\Controllers\Admin\ProfileController::class, 'updateProfile'])->name('profile.update');
            Route::get('change-password', [App\Http\Controllers\Admin\ProfileController::class, 'changePassword'])->name('profile.change_password');
            Route::post('update-password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.update_password');

            Route::get('sall-product-report', [App\Http\Controllers\Admin\ReportController::class, 'sallProductReport'])->name('sallProductReport')->middleware('permission:sallProductReport.view');
            Route::post('sall-product-report-post', [App\Http\Controllers\Admin\ReportController::class, 'sallProductReport'])->name('sallProductReportPost')->middleware('permission:sallProductReport.view');

            Route::get('rent-product-report', [App\Http\Controllers\Admin\ReportController::class, 'rentProductReport'])->name('rentProductReport')->middleware('permission:rentProductReport.view');
            Route::post('rent-product-report-post', [App\Http\Controllers\Admin\ReportController::class, 'rentProductReport'])->name('rentProductReportPost')->middleware('permission:rentProductReport.view');

            Route::get('reports/sale-orders', [App\Http\Controllers\Admin\ReportController::class, 'saleOrders'])->name('reports.saleOrders')->middleware('permission:reports.saleOrders');
            Route::get('reports/rent-orders', [App\Http\Controllers\Admin\ReportController::class, 'rentOrders'])->name('reports.rentOrders')->middleware('permission:reports.rentOrders');

            // Businesses CRUD
            
            Route::get('solutions', [\App\Http\Controllers\Admin\SolutionController::class, 'index'])->name('solutions.index');
            Route::get('solutions/create', [\App\Http\Controllers\Admin\SolutionController::class, 'create'])->name('solutions.create');
            Route::post('solutions', [\App\Http\Controllers\Admin\SolutionController::class, 'store'])->name('solutions.store');
            Route::get('solutions/{id}/edit', [\App\Http\Controllers\Admin\SolutionController::class, 'edit'])->name('solutions.edit');
            Route::put('solutions/{id}', [\App\Http\Controllers\Admin\SolutionController::class, 'update'])->name('solutions.update');
            Route::delete('solutions/{id}', [\App\Http\Controllers\Admin\SolutionController::class, 'destroy'])->name('solutions.destroy');
            Route::get('solutions/list', [\App\Http\Controllers\Admin\SolutionController::class, 'list'])->name('solutions.list');
    
            // Solution Types CRUD
            Route::get('solution-types', [\App\Http\Controllers\Admin\SolutionTypeController::class, 'index'])->name('solution-types.index')->middleware('permission:solution_types.view');
            Route::get('solution-types/create', [\App\Http\Controllers\Admin\SolutionTypeController::class, 'create'])->name('solution-types.create')->middleware('permission:solution_types.create');
            Route::post('solution-types', [\App\Http\Controllers\Admin\SolutionTypeController::class, 'store'])->name('solution-types.store')->middleware('permission:solution_types.create');
            Route::get('solution-types/{id}/edit', [\App\Http\Controllers\Admin\SolutionTypeController::class, 'edit'])->name('solution-types.edit')->middleware('permission:solution_types.edit');
            Route::put('solution-types/{id}', [\App\Http\Controllers\Admin\SolutionTypeController::class, 'update'])->name('solution-types.update')->middleware('permission:solution_types.edit');
            Route::delete('solution-types/{id}', [\App\Http\Controllers\Admin\SolutionTypeController::class, 'destroy'])->name('solution-types.destroy')->middleware('permission:solution_types.delete');
            Route::get('solution-types/list', [\App\Http\Controllers\Admin\SolutionTypeController::class, 'list'])->name('solution-types.list')->middleware('permission:solution_types.view');
            Route::get('solution-types/select', [\App\Http\Controllers\Admin\SolutionTypeController::class, 'select'])->name('solution-types.select');
            
            Route::get('products/list', [App\Http\Controllers\Admin\ProductController::class, 'list'])->name('products.list')->middleware('permission:products.view');
            Route::post('products', [App\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store')->middleware('permission:products.create');
            Route::delete('products/{id}', [App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy')->middleware('permission:products.delete');
            Route::get('products', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index')->middleware('permission:products.view');
            Route::get('products/create', [App\Http\Controllers\Admin\ProductController::class, 'create'])->name('products.create')->middleware('permission:products.create');
            Route::match(['PUT', 'PATCH'], 'products/{id}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update')->middleware('permission:products.edit');
            Route::get('products/{id}/edit', [App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('products.edit')->middleware('permission:products.edit');

            // Industries routes
            Route::prefix('industries')->name('industries.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\IndustryController::class, 'index'])->name('index');
                Route::get('/list', [\App\Http\Controllers\Admin\IndustryController::class, 'list'])->name('list');
                Route::get('/create', [\App\Http\Controllers\Admin\IndustryController::class, 'create'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\IndustryController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [\App\Http\Controllers\Admin\IndustryController::class, 'edit'])->name('edit');
                Route::put('/{id}', [\App\Http\Controllers\Admin\IndustryController::class, 'update'])->name('update');
                Route::delete('/{id}', [\App\Http\Controllers\Admin\IndustryController::class, 'destroy'])->name('destroy');
            });

            //parteners
            Route::get('parteners', [App\Http\Controllers\Admin\PartenerController::class, 'index'])->name('parteners.index')->middleware('permission:parteners.view');
            Route::get('parteners/create', [App\Http\Controllers\Admin\PartenerController::class, 'create'])->name('parteners.create')->middleware('permission:parteners.create');
            Route::post('parteners', [App\Http\Controllers\Admin\PartenerController::class, 'store'])->name('parteners.store')->middleware('permission:parteners.create');
            Route::match(['PUT', 'PATCH'], 'parteners/{id}', [App\Http\Controllers\Admin\PartenerController::class, 'update'])->name('parteners.update')->middleware('permission:parteners.edit');
            Route::get('parteners/{id}/edit', [App\Http\Controllers\Admin\PartenerController::class, 'edit'])->name('parteners.edit')->middleware('permission:parteners.edit');
            Route::delete('parteners/{id}', [App\Http\Controllers\Admin\PartenerController::class, 'destroy'])->name('parteners.destroy')->middleware('permission:parteners.delete');
            Route::get('parteners/list', [App\Http\Controllers\Admin\PartenerController::class, 'list'])->name('parteners.list')->middleware('permission:parteners.view');
            //addnewrouteheredontdeletemeplease

            // Articles Module
            Route::prefix('articles')->name('articles.')->middleware('can:articles.view')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\ArticleController::class, 'index'])->name('index');
                Route::get('/list', [\App\Http\Controllers\Admin\ArticleController::class, 'list'])->name('list');
                Route::get('/create', [\App\Http\Controllers\Admin\ArticleController::class, 'create'])->middleware('can:articles.create')->name('create');
                Route::post('/store', [\App\Http\Controllers\Admin\ArticleController::class, 'store'])->middleware('can:articles.create')->name('store');
                Route::get('/edit/{id}', [\App\Http\Controllers\Admin\ArticleController::class, 'edit'])->middleware('can:articles.edit')->name('edit');
                Route::post('/update/{id}', [\App\Http\Controllers\Admin\ArticleController::class, 'update'])->middleware('can:articles.edit')->name('update');
                Route::delete('/destroy/{id}', [\App\Http\Controllers\Admin\ArticleController::class, 'destroy'])->middleware('can:articles.delete')->name('destroy');
                Route::get('/search', [\App\Http\Controllers\Admin\ArticleController::class, 'search'])->name('search');
            });

            // Pages Module
            Route::prefix('pages')->name('pages.')->middleware('can:pages.view')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\PageController::class, 'index'])->name('index')->middleware('permission:pages.view');
                Route::get('/list', [App\Http\Controllers\Admin\PageController::class, 'list'])->name('list')->middleware('permission:pages.view');
                Route::get('/create', [App\Http\Controllers\Admin\PageController::class, 'create'])->name('create')->middleware('permission:pages.create');
                Route::post('/store', [App\Http\Controllers\Admin\PageController::class, 'store'])->name('store')->middleware('permission:pages.create');
                Route::get('/edit/{id}', [App\Http\Controllers\Admin\PageController::class, 'edit'])->name('edit')->middleware('permission:pages.edit');
                Route::post('/update/{id}', [App\Http\Controllers\Admin\PageController::class, 'update'])->name('update')->middleware('permission:pages.edit');
                Route::delete('/destroy/{id}', [App\Http\Controllers\Admin\PageController::class, 'destroy'])->name('destroy')->middleware('permission:pages.delete');
                Route::get('/show/{id}', [App\Http\Controllers\Admin\PageController::class, 'show'])->name('show')->middleware('permission:pages.view');

                // دعم البحث عن المقالات من خلال المسار المطلوب من الواجهة
                Route::get('articles/search', [\App\Http\Controllers\Admin\ArticleController::class, 'search']);

                // Sections Module (Nested under Pages)
                Route::prefix('{page_id}/sections')->name('sections.')->middleware('can:sections.view')->group(function () {
                    Route::get('/', [App\Http\Controllers\Admin\SectionController::class, 'index'])->name('index')->middleware('permission:sections.view');
                    Route::get('/create', [App\Http\Controllers\Admin\SectionController::class, 'create'])->name('create')->middleware('permission:sections.create');
                    Route::post('/store', [App\Http\Controllers\Admin\SectionController::class, 'store'])->name('store')->middleware('permission:sections.create');
                    Route::get('/edit/{id}', [App\Http\Controllers\Admin\SectionController::class, 'edit'])->name('edit')->middleware('permission:sections.edit');
                    Route::post('/update/{id}', [App\Http\Controllers\Admin\SectionController::class, 'update'])->name('update')->middleware('permission:sections.edit');
                    Route::delete('/destroy/{id}', [App\Http\Controllers\Admin\SectionController::class, 'destroy'])->name('destroy')->middleware('permission:sections.delete');
                    Route::get('/show/{id}', [App\Http\Controllers\Admin\SectionController::class, 'show'])->name('show')->middleware('permission:sections.view');
                    Route::get('/list', [App\Http\Controllers\Admin\SectionController::class, 'list'])->name('list')->middleware('permission:sections.view');
                    Route::post('/sort', [App\Http\Controllers\Admin\SectionController::class, 'sort'])->name('sort');

                    // إدارة عناصر السكشن
                    Route::prefix('{section}/items')->name('items.')->group(function () {
                        Route::post('/', [\App\Http\Controllers\Admin\SectionItemController::class, 'store'])->name('store');
                        Route::delete('/{item}', [\App\Http\Controllers\Admin\SectionItemController::class, 'destroy'])->name('destroy');
                        Route::post('/sort', [\App\Http\Controllers\Admin\SectionItemController::class, 'sort'])->name('sort');
                    });
                });
            });
        });
    });
});