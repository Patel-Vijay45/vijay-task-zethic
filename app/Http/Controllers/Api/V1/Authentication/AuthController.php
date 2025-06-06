<?php

namespace App\Http\Controllers\Api\V1\Authentication;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(private UserService $userService) {}
    /** 
     *  User Register.
     *
     * @unauthenticated 
     */
    public function register(UserRegisterRequest $request)
    {
        $user = $this->userService->createUser($request->validated());
        return ResponseHelper::sendSuccess('Register Successfully Please Check you email & verify');
    }
    /** 
     *  User Login.
     *
     * @unauthenticated 
     */
    public function login(LoginRequest $request)
    {
        $request->authenticate();
        $user = Auth::user();

        $token = $user->createToken('Token', ['role:' . $user->role])->plainTextToken;
        return ResponseHelper::sendSuccess('Login Successfully', ['data' => UserResource::make($user), 'token' => $token]);
    }
    /** 
     * User Profile.
     * 
     */
    public function profile(Request $request)
    {
        return ResponseHelper::sendSuccess('Data fetch Successfully',  UserResource::make($request->user()));
    }
    /** 
     * User Logout
     * 
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return ResponseHelper::sendSuccess('Logout Successfully');
    }
}
