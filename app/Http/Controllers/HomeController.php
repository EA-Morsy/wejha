<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function language()
    {

        if(Session::get('lang')=='en'){
            Session::put('lang', 'ar');
        }else{
            Session::put('lang', 'en');
        }
        return back();

    }
    public function privacy()
    {

        if(App::isLocale('en')) {
            return view('privacy_en');
        }else{
            return view('privacy_ar');
        }

    }
    public function database(Request $request)
    {
        $centralCompany = User::first();
        $this->createCompanyDatabase($centralCompany);
        $this->switchDatabase($centralCompany->id);
        $this->runMigrationsAndSeeders();
    }

        protected function createCompanyDatabase($company)
    {
        $dbName = 'company_' . $company->id;
        DB::purge('mysql');
        DB::statement("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        DB::reconnect('mysql');
    }
     protected function switchDatabase($companyId)
    {
        config(['database.connections.company.database' => 'company_' . $companyId]);
        DB::purge('company');
        DB::reconnect('company');
        Artisan::call('config:clear');
    }
       protected function runMigrationsAndSeeders()
    {
        Artisan::call('migrate', ['--database' => 'company']);
        Artisan::call('db:seed', ['--database' => 'company']);
        // Artisan::call('passport:install', ['--force' => true]);
    }



}
