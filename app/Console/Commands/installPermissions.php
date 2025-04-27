<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;

class installPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command Custom for install permissions for application that used spatie\laravel-permessions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //$this->installStaticPermissions();
        $this->installRoutesPermissions();
        $this->assignPermissionsToAdmin();
        // $this->recurseCopy("resources/views/admin/pages/categories","resources/views/admin/pages/cities","cities",'City');

        return 0;
    }

    private function installRoutesPermissions(): void
    {
        $permissions = Permission::where('guard_name', 'web')->get()->pluck('name')->toArray();
        $routes = Route::getRoutes();
        $arr =[];
        $tempPermissions = [];
        foreach ($routes as $route) {
            $middleware = $route->middleware();
            if (is_array($middleware)) {
                foreach ($middleware as $middleware) {
                    if (strpos($middleware, 'permission:') > -1) {
                        $permission = explode(':', $middleware);
                        if (!in_array($permission[1], $tempPermissions) && !in_array($permission[1], $permissions)) {
                            array_push($tempPermissions, $permission[1]);
                            $group = explode('.', $permission[1]);
                            $arr[] =[
                                'name' => $permission[1],
                                'display_name' => $permission[1],
                                'group' => $group[0] ?? null,
                                'guard_name' => 'web',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }
                }
            }
        }
        if (count($arr) > 0) {
            Permission::insert($arr);
            $this->info('Routes Permissions installed');
        }
    }

    private function installStaticPermissions(): void
    {
        if (file_exists(base_path('/static_permissions.json'))) {
            $internalPermissions = file_get_contents(base_path('/static_permissions.json'));
            $internalPermissions = json_decode((string) $internalPermissions);

            foreach ($internalPermissions as $permission) {
                $web = Permission::where('name', $permission)->where('guard_name', 'web')->first();
                if ($web == null) {
                    Permission::create(['name' => $permission, 'guard_name' => 'web']);
                }
            }
            $this->info('Static Permissions Installed');
        }
    }

    private function assignPermissionsToAdmin(): void
    {
        $user = User::where('email', 'admin@admin.com')->first();
        $role = Role::where('name', 'admin')->first();

        if ($user && $role) {
            $role->syncPermissions(Permission::all());
            $user->syncRoles($role->id);
            Artisan::call('cache:clear');
            $this->info("All Permissions assigned to user {$user->email}");
        }
    }

    function recurseCopy(
        string $sourceDirectory,
        string $destinationDirectory,
        string $model_name ,
        string $model ): void {
        $directory = opendir($sourceDirectory);
        if (is_dir($destinationDirectory) === false) {
            mkdir($destinationDirectory);
        }
        while (($file = readdir($directory)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            if (is_dir("$sourceDirectory/$file") === true) {
                recurseCopy("$sourceDirectory/$file", "$destinationDirectory/$file");
            } else {
                copy("$sourceDirectory/$file", "$destinationDirectory/$file");
            }
            $file_content = file_get_contents("$destinationDirectory/$file");
            $str = str_replace("categories",$model_name, $file_content);
            file_put_contents("$destinationDirectory/$file", $str);
        }
        copy('app/Http/Controllers/Admin/CategoryController.php', 'app/Http/Controllers/Admin/'.$model.'Controller.php');

        $file_content = file_get_contents('app/Http/Controllers/Admin/'.$model.'Controller.php');
        $str = str_replace("categories", $model_name, $file_content);
        file_put_contents('app/Http/Controllers/Admin/'.$model.'Controller.php', $str);

        $file_content = file_get_contents('app/Http/Controllers/Admin/'.$model.'Controller.php');
        $str = str_replace("Category", $model, $file_content);
        file_put_contents('app/Http/Controllers/Admin/'.$model.'Controller.php', $str);

        copy('app/Http/Requests/CategoryRequest.php', 'app/Http/Requests/'.$model.'Request.php');
        $file_content = file_get_contents('app/Http/Requests/'.$model.'Request.php');
        $str = str_replace("Category", $model, $file_content);
        file_put_contents('app/Http/Requests/'.$model.'Request.php', $str);

        copy('lang/en/categories.php', 'lang/en/'.$model_name.'.php');
        copy('lang/ar/categories.php', 'lang/ar/'.$model_name.'.php');

        closedir($directory);
    }

}
