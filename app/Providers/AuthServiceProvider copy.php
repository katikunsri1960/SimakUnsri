<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Http\Controllers\AuthorizationController;
use Laravel\Passport\Http\Controllers\TransientTokenController;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        $this->registerPolicies();
        
        // Manual route registration untuk Passport
        $this->registerPassportRoutes();
    }

    protected function registerPassportRoutes(): void
    {
        Route::group([
            'as' => 'passport.',
            'prefix' => 'oauth',
            'middleware' => ['web', 'auth']
        ], function () {
            // Authorization routes
            Route::get('/authorize', [AuthorizationController::class, 'authorize'])
                ->name('authorizations.authorize');
            Route::post('/authorize', [AuthorizationController::class, 'approve'])
                ->name('authorizations.approve');
            Route::delete('/authorize', [AuthorizationController::class, 'deny'])
                ->name('authorizations.deny');

            // Token routes
            Route::post('/token', [AccessTokenController::class, 'issueToken'])
                ->name('token');
            Route::get('/tokens', [TransientTokenController::class, 'index'])
                ->name('tokens.index');
            Route::delete('/tokens/{token_id}', [TransientTokenController::class, 'destroy'])
                ->name('tokens.destroy');
        });
    }
}