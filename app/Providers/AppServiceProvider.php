<?php

namespace App\Providers;

use App\Services\OfxParser;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        App::bind('ofxParser', OfxParser::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        if (env('APP_DEBUG_SQL', false)) {
            DB::listen(function ($query) {
                $sql = $query->sql;
                $bindings = $query->bindings;
                $time = $query->time;
                $connectionName = $query->connectionName;
                $sql = str_replace('?', "'%s'", $sql);
                $fullSql = vsprintf($sql, $bindings);
                Log::debug("[$connectionName] $fullSql ($time ms)");
            });
        }
    }
}
