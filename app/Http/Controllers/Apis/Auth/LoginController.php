<?php

namespace App\Http\Controllers\Apis\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\traits\ApiTrait;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use ApiTrait;
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (! Hash::check($request->password, $user->password)) {
            return $this->ErrorMessage(['email' => 'the provided credentials are incorrect'], 'wrong attemp');
        }
        $user->token = "Bearer " . $user->createToken($request->device_name)->plainTextToken;
        if (is_null($user->email_verified_at)) {
            return  $this->Data(compact('user'), 'user not verifeid', 401);
        }
        return $this->Data(compact('user'));
    }

    public function logout(Request $request)
    {
        $authenticatedUser = Auth::guard('sanctum')->user();
        $token = $request->header('Authorization');
        $brearWithId = explode('|', $token)[0];
        $tokenId = explode(' ', $brearWithId)[1];
        $authenticatedUser->tokens()->where('id', $tokenId)->delete();
        return $this->SuccessMessage("usel has been logged out successfuly");
    }

    public function logoutAllDevices()
    {
        $authenticatedUser = Auth::guard('sanctum')->user();
        $authenticatedUser->tokens()->delete();
        return $this->SuccessMessage("usel has been logged out successfuly from all devices");
    }
}
