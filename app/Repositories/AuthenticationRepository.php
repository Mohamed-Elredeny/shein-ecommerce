<?php

namespace App\Repositories;

use App\Interfaces\AuthenticationRepositoryInterface;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthenticationRepository implements AuthenticationRepositoryInterface
{
    public function login($recordDetails)
    {
        $email = $recordDetails['emailOrPhone'];
        $phone = $recordDetails['emailOrPhone'] ?? '';
        $password = $recordDetails['password'];

        $users_with_email = User::where('email', $email)->first();
        if ($users_with_email) {
            if (Hash::check($password, $users_with_email->password)) {
                return $this->response(1, $users_with_email);
            } else {
                return $this->response(0, []);
            }
        } else {
            $users_with_phone = User::where('phone', $phone)->first();
            if ($users_with_phone) {
                if (Hash::check($password, $users_with_phone->password)) {
                    return $this->response(1, $users_with_phone);
                } else {
                    return $this->response(0, []);
                }
            } else {
                return $this->response(0, []);
            }
        }


    }

    public function response($status, $data)
    {
        return [
            'status' => boolval($status),
            'data' => $data
        ];
    }

    public function loginSocial(array $recordDetails)
    {
        // TODO: Implement loginSocial() method.
    }

    public function register(array $recordDetails)
    {
        $user = User::create($recordDetails);

        if ($user) {
            return $this->response(1, $user);
        }
        return $this->response(0, []);
    }

    public function logout($token)
    {

        $user = User::where('remember_token', $token)->first();
        if ($user) {
            Auth::logout();
            $user->update(['remember_token' => null]);
            return $this->response(1, $user);
        } else {
            return $this->response(0, []);
        }
    }

    public function viewProfile($token)
    {
        return User::where('remember_token', $token)->first();
    }

    public function updateProfile($token, array $recordDetails)
    {
        // TODO: Implement updateProfile() method.
    }

    public function updateToken(Request $request, $user)
    {
        $credentials =[
            'email'=>$request->emailOrPhone,
            'password'=>$request->password
        ];
        $token = Auth::guard('api')->attempt($credentials);
        if (!$token) {
            $credentials =[
                'phone'=>$request->emailOrPhone,
                'password'=>$request->password
            ];
            $token = Auth::guard('api')->attempt($credentials);
        }
        $user->update([
            'remember_token' => $token
        ]);
    }
}
