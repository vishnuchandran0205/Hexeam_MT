<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $email=request('email');
        $password=request('password');
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $check_valid=User::where('email',$email)->first();
       // print_r($check_valid);die;
        if ($check_valid && password_verify($password, $check_valid->password)) {
            $request->session()->put('userId', $check_valid->id);
           // $request->session()->put('name', $check_valid->name);
            $request->session()->put('user_type', $check_valid->user_type);
            $request->session()->regenerate();
            // $userId = $request->session()->get('userId');
            $user_type = $request->session()->get('user_type');
           
            if($user_type == 1){
                return redirect()->intended(RouteServiceProvider::Administrator);
            }elseif($user_type == 2){
                return redirect()->intended(RouteServiceProvider::Sub_admin);
            }else{
                return redirect('/login');
            }

            
        } else {
            print_r('error');die;
        }
        
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
