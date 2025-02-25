<?php

namespace App\Http\Controllers\Apis\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\traits\ApiTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    use ApiTrait;
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request)
    {
        // validation
        $data = $request->except('password', 'password_confirmation');
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);
        $user->token = "Bearer " . $user->createToken($request->device_name)->plainTextToken;
        return $this->Data(compact('user'), '', 201);
    }
}
