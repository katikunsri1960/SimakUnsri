<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    public function login(Request $req)
    {
        $input = $req->all();
        $nim = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '20'];
        $username = $input['username'];
        // check left 2 digit of username
        $left2digit = substr($username, 0, 2);

        if (in_array($left2digit, $nim)) {
            $user = RiwayatPendidikan::where('nim', $username)->first();
            $akun = User::where('username', $username)->first();
            if ($user && !$akun) {
                return redirect()->route('create-account-mahasiswa');
            }
        }

        $this->validate($req, [
            'username' => 'required|exists:users,username',
            'password' => 'required',
        ]);

        $roleToRouteMap = [
            User::ADMINISTRATOR => 'admin.dashboard-admin',
            User::ADMIN_UNIVERSITAS => 'univ',
            User::ADMIN_FAKULTAS => 'fakultas',
            User::ADMIN_PRODI => 'prodi',
            User::DOSEN => 'dosen',
            User::MAHASISWA => 'mahasiswa.dashboard',
            User::BAAK => 'bak',
            User::PERPUS => 'perpus',
        ];

        if (auth()->attempt(['username' => $input['username'], 'password' => $input['password']])) {
            $userRole = auth()->user()->role;

            // Step 2: Use the user's role to look up the redirect route
            if (array_key_exists($userRole, $roleToRouteMap)) {
                return redirect()->route($roleToRouteMap[$userRole]);
            } else {
                return redirect()->route('login')->with('error', 'Username or password is incorrect');
            }
        } else {
            return redirect()->route('login')->with('error', 'Username or password is incorrect');
        }

    }


}
