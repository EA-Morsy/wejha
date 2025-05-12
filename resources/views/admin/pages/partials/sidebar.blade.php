<?php
    $route = \Route::currentRouteName();
    $assetsPath = asset('assets/admin');
?>
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true" style="overflow-y:auto">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto">
                <a class="navbar-brand" href="{{ route('admin.home') }}">
                    <h2 class="brand-text">{{ config('app.name') }}</h2>
                </a>
            </li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            {{-- Dashboard --}}
            <li class="nav-item {{ request()->routeIs('admin.home') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('admin.home') }}">
                    <i data-feather="home"></i>
                    <span class="menu-title text-truncate" data-i18n="Dashboards">{{ __('admin.dashboard') }}</span>
                </a>
            </li>

            {{-- إدارة المحتوى --}}
            @if(auth()->user()->can('articles.view') || auth()->user()->can('categories.view') || auth()->user()->can('sliders.view'))
            <li class="nav-item has-sub {{ request()->routeIs('admin.articles.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.sliders.*') ? 'open' : '' }}">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="edit-3"></i>
                    <span class="menu-title text-truncate">إدارة المحتوى</span>
                </a>
                <ul class="menu-content">
                    @can('articles.view')
                    <li class="{{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.articles.index') }}">
                            <i data-feather="file-text"></i>
                            <span class="menu-item text-truncate">{{ __('admin.articles') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('categories.view')
                    <li class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.categories.index') }} ">
                            <i data-feather="list"></i>
                            <span class="menu-item text-truncate">{{ __('admin.categories') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('sliders.view')
                    <li class="{{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.sliders.index') }} ">
                            <i data-feather="image"></i>
                            <span class="menu-item text-truncate">{{ __('admin.sliders') }}</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endif

            {{-- إدارة الأعمال --}}
            @if(auth()->user()->can('businesses.view') || auth()->user()->can('parteners.view') || auth()->user()->can('industries.view'))
            <li class="nav-item has-sub {{ request()->routeIs('admin.solutions.*') || request()->routeIs('admin.parteners.*') || request()->routeIs('admin.industries.*') ? 'open' : '' }}">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="folder"></i>
                    <span class="menu-title text-truncate">إدارة الأعمال</span>
                </a>
                <ul class="menu-content">
                    @can('businesses.view')
                    <li class="{{ request()->routeIs('admin.solutions.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.solutions.index') }}">
                            <i data-feather="briefcase"></i>
                            <span class="menu-item text-truncate">{{ __('admin.solutions') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('parteners.view')
                    <li class="{{ request()->routeIs('admin.parteners.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.parteners.index') }} ">
                            <i data-feather="users"></i>
                            <span class="menu-item text-truncate">{{ __('admin.parteners') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('industries.view')
                    <li class="{{ request()->routeIs('admin.industries.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.industries.index') }} ">
                            <i data-feather="layers"></i>
                            <span class="menu-item text-truncate">{{ __('admin.industries') }}</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endif

            {{-- إدارة الحلول --}}
            <li class="nav-item has-sub {{ request()->routeIs('admin.solutions.*') || request()->routeIs('admin.solution-types.*') || request()->routeIs('admin.products.*') ? 'open' : '' }}">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="box"></i>
                    <span class="menu-title text-truncate">إدارة الحلول</span>
                </a>
                <ul class="menu-content">
                    <li class="{{ request()->routeIs('admin.solutions.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.solutions.index') }}">
                            <i data-feather="briefcase"></i>
                            <span class="menu-item text-truncate">الحلول</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.solution-types.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.solution-types.index') }}">
                            <i data-feather="tag"></i>
                            <span class="menu-item text-truncate">أنواع الحلول</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('admin.products.*') || request()->routeIs('admin.product_specs.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.products.index') }}">
                            <i data-feather="package"></i>
                            <span class="menu-item text-truncate">المنتجات</span>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- إدارة المستخدمين --}}
            @if(auth()->user()->can('users.view') || auth()->user()->can('roles.view'))
            <li class="nav-item has-sub {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') ? 'open' : '' }}">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="user-check"></i>
                    <span class="menu-title text-truncate">إدارة المستخدمين</span>
                </a>
                <ul class="menu-content">
                    @can('users.view')
                    <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.users.index') }} ">
                            <i data-feather="users"></i>
                            <span class="menu-item text-truncate">{{ __('admin.users') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('roles.view')
                    <li class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.roles.index') }} ">
                            <i data-feather="lock"></i>
                            <span class="menu-item text-truncate">{{ __('admin.roles') }}</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endif

            {{-- الإعدادات --}}
            @if(auth()->user()->can('settings.general') || auth()->user()->can('settings.privacy') || auth()->user()->can('settings.terms'))
            <li class="nav-item has-sub {{ request()->routeIs('admin.settings.index') || request()->routeIs('admin.settings.privacy') || request()->routeIs('admin.settings.terms') ? 'open' : '' }}">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather="settings"></i>
                    <span class="menu-title text-truncate">الإعدادات</span>
                </a>
                <ul class="menu-content">
                    @can('settings.general')
                    <li class="{{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.settings.index') }} ">
                            <i data-feather="settings"></i>
                            <span class="menu-item text-truncate">{{ __('admin.company_details') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('settings.privacy')
                    <li class="{{ request()->routeIs('admin.settings.privacy') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.settings.privacy') }} ">
                            <i data-feather="help-circle"></i>
                            <span class="menu-item text-truncate">{{ __('admin.privacy') }}</span>
                        </a>
                    </li>
                    @endcan
                    @can('settings.terms')
                    <li class="{{ request()->routeIs('admin.settings.terms') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.settings.terms') }} ">
                            <i data-feather="key"></i>
                            <span class="menu-item text-truncate">{{ __('admin.terms') }}</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endif

            {{-- إدارة الصفحات --}}
            @can('pages.view')
            <li class="nav-item {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('admin.pages.index') }}">
                    <i data-feather="file"></i>
                    <span class="menu-title text-truncate">{{ __('admin.pages') }}</span>
                </a>
            </li>
            @endcan
            {{--addnewrouteheredontdeletemeplease--}}
        </ul>
    </div>
</div>
