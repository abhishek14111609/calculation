<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        // Fix for open_basedir restriction: Ensure safe temporary path
        $tempPath = storage_path('framework/cache/laravel-excel');

        if (!file_exists($tempPath)) {
            @mkdir($tempPath, 0775, true);
        }

        // Force PhpSpreadsheet to use this safe path globally if supported
        if (class_exists(\PhpOffice\PhpSpreadsheet\Shared\File::class) && method_exists(\PhpOffice\PhpSpreadsheet\Shared\File::class, 'setSysTempDir')) {
            \PhpOffice\PhpSpreadsheet\Shared\File::setSysTempDir($tempPath);
        }

        // Ensure Laravel Excel config respects this path
        config(['excel.temporary_files.local_path' => $tempPath]);
    }
}
