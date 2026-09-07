<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {

                if (auth()->user()->role == 'admin') {
                    return redirect(RouteServiceProvider::ADMIN);
                } elseif (auth()->user()->role === 'universitas') {
                    return redirect(RouteServiceProvider::UNIVERSITAS);
                } elseif (auth()->user()->role === 'fakultas') {
                    return redirect(RouteServiceProvider::FAKULTAS);
                } elseif (auth()->user()->role === 'prodi') {
                    return redirect(RouteServiceProvider::PRODI);
                } elseif (auth()->user()->role === 'mahasiswa') {
                    return redirect(RouteServiceProvider::MAHASISWA);
                }elseif (auth()->user()->role === 'perpus') {
                    return redirect(RouteServiceProvider::PERPUS);
                } elseif (auth()->user()->role === 'dppti') {
                    return redirect(RouteServiceProvider::DPPTI);
                }elseif (auth()->user()->role === 'dak') {
                    return redirect(RouteServiceProvider::DAK);
                } elseif (auth()->user()->role === 'ditmawa') {
                    return redirect(RouteServiceProvider::DITMAWA);
                } elseif (auth()->user()->role === 'dppm') {
                    return redirect(RouteServiceProvider::DPPM);
                } elseif (auth()->user()->role === 'wali') {
                    return redirect(RouteServiceProvider::WALI);
                }

            }
        }

        return $next($request);
    }
}
