<?php

namespace App\Http\Controllers\Apis\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckCodeRequest;
use App\Http\traits\ApiTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    use ApiTrait;
    public function sendCode(Request $request)
    {
        // token
        $token = $request->header('Authorization');
        // get user
        $authenticatedUser = Auth::guard('sanctum')->user();
        //generate code 
        $code = rand(10000, 99999);
        // generate expiration date 
        $code_expired_at = date('Y-m-d H:i:s', strtotime('+2 minutes'));
        // save code with date in DB
        $user = User::find($authenticatedUser->id);
        $user->code = $code;
        $user->code_expired_at = $code_expired_at;
        $user->save();
        $user->token = $token;
        // send email 
        // return user data
        return $this->Data(compact('user'));
    }

    public function checkCode(CheckCodeRequest $request)
    {
        // token => header
        $token = $request->header('Authorization');
        $authenticatedUser = Auth::guard('sanctum')->user();
        $user = User::find($authenticatedUser->id);
        // check if code correct in db

        if ($user->code == $request->code && $user->code_expired_at < date('Y-m-d H:i:s')) {
            // update email verified at 
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->save();
            $user->token = $token;
            return $this->Data(compact('user'));
        } else {
            $user->token = $token;
            return $this->ErrorMessage(['code' => 'code invalid'], 'Failed Attemp', 401);
        }

        // code must be not expired 


    }
}
