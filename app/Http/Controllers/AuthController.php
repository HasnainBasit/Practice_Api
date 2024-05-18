<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\AuthRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use App\Http\Resources\UserActionResource;
use App\Http\Resources\LoginResource;
use App\Http\Resources\LogoutResource;
use App\Http\Resources\PasswordResetResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetEmail;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api',['except'=>['login','register','sendResetLinkEmail','reset']]);
    }
    public function register(AuthRequest $request)
    {
        $request->validate($request->rulesForRegistration());
    
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $request['password'],
            'phone_number' => $request['phone_number'],
            'status' => $request['status'],
        ]);
    
        $userResource = new UserActionResource([
            'message' => 'User registered successfully',
            'data' => $user,
        ]);
    
        return $userResource->successResponse('register', 'User registered successfully', $user, 201);
    }
    public function login(AuthRequest $request)
    {
        $validator = $request->validate($request->rulesForLogin());

        if (!$token = auth()->attempt($validator)) {
            return (new LoginResource([
                'action' => 'login',
                'message' => 'Invalid Credintials',
            ]))->errorResponse('login', 'Invalid Credintials', 401);
        }

        $userResource = new LoginResource([
            'action' => 'login',
            'message' => 'Login successful',
            'user' => auth()->user(),
            'token' => $token,
        ]);

        return $userResource->successResponse('login');
    }
    public function profile(AuthRequest $request){
        return response()->json(auth()->user());
    }
    public function logout()
    {
        auth()->logout();

        $logoutResource = new LogoutResource([
            'action' => 'logout',
            'message' => 'User logged out',
        ]);
    
        return $logoutResource->successResponse('logout');
    }
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink(
            $request->only('email')
        );
    
        $resetResource = new PasswordResetResource(['status' => $status]);
    
        return $status === Password::RESET_LINK_SENT
            ? $resetResource->successResponse('send_reset_link_email')
            : $resetResource->errorResponse('send_reset_link_email', 400);
    }
    
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|max:6',
        ]);
    
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );
    
        $resetResource = new PasswordResetResource(['status' => $status]);
    
        return $status == Password::PASSWORD_RESET
            ? $resetResource->successResponse('reset_password')
            : $resetResource->errorResponse('reset_password', 400);
    }
}

