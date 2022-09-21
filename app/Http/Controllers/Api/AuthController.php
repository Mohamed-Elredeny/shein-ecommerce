<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\AuthenticationRepositoryInterface;
use App\Mail\DemoEmailApiForegtPassword;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\GeneralTrait;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use GeneralTrait;

    public $userAuthentication;

    public function __construct(AuthenticationRepositoryInterface $userAuthentication)
    {
        $this->userAuthentication = $userAuthentication;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'emailOrPhone' => 'required',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return $this->returnValidationError(422, $validator);
        } else {
            $recordDetails = [
                'emailOrPhone' => $request->emailOrPhone,
                'password' => $request->password,
            ];

            $user_login = $this->userAuthentication->login($recordDetails);
            if ($user_login['status']) {
                $this->updateToken($request, $user_login['data']);
                return $this->returnData(['userDetails'], [$user_login['data']], 'User login successfully');
            } else {
                return $this->returnError(200, 'Unable to login');
            }
        }
    }

    public function updateToken(Request $request, $user)
    {
        $credentials = [
            'email' => $request->emailOrPhone,
            'password' => $request->password
        ];
        $token = Auth::guard('api')->attempt($credentials);
        if (!$token) {
            $credentials = [
                'phone' => $request->emailOrPhone,
                'password' => $request->password
            ];
            $token = Auth::guard('api')->attempt($credentials);
        }
        $user->update([
            'remember_token' => $token
        ]);
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:5|max:255',
            'phone' => 'required|string|min:9|unique:users',
            'email' => 'required|string|email|min:5|max:255|unique:users',
            'password' => 'required|string|min:6',
            'image' => 'nullable',
            'address.area_id' => 'nullable',
            'address.directions.street_name' => 'nullable|string',
            'address.directions.building_number' => 'nullable|string',
            'address.directions.floor_number' => 'nullable|string',
            'address.directions.flat_number' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return $this->returnValidationError(422, $validator);
        } else {
            if ($request->image) {
                $image = $this->uploadImage($request, 'image', 'users');
            } else {
                $image = null;
            }
            $recordDetails = [
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => hash::make($request->password),
                'image' => $image,
            ];

            $response = $this->userAuthentication->register($recordDetails);
            $user = $response['data'];
            $credentials = [
                'email' => $user->email,
                'password' => $request->password
            ];
            $token = Auth::guard('api')->attempt($credentials);
            $user->update([
                'remember_token' => $token
            ]);
            if ($request->address) {
                $address = [
                    'area_id' => $request['address']['area_id'],
                    'street_name' => $request['address']['directions']['street_name'],
                    'building_number' => $request['address']['directions']['building_number'],
                    'floor_number' => $request['address']['directions']['floor_number'],
                    'flat_number' => $request['address']['directions']['flat_number'],
                ];
                \App\Models\UserAddress::create([
                    'user_id'=>$user->id,
                    'area_id'=> intval($address['area_id']),
                    'street_name'=>$address['street_name'],
                    'building_number'=>$address['building_number'],
                    'floor_number'=>$address['floor_number'],
                    'flat_number'=>$address['flat_number'],
                    'is_default'=>1
                ]);
            }
            return $this->returnData(['userDetails'], [$user]);
        }

    }

    public function profile(Request $request, $profile)
    {
        $token = $request->header('token');
        $auth_user = $this->userAuthentication->viewProfile($token);
        $wanted_user = User::find($profile);
        if ($wanted_user) {
            if ($profile == $auth_user->id) {
                $auth_user->myProfile = 1;
                return $this->returnData(['userDetails'], [$auth_user], 'userDetails');
            } else {
                $wanted_user->myProfile = 0;
                return $this->returnData(['userDetails'], [$wanted_user], 'userDetails');
            }
        } else {
            return $this->returnError(200, 'There is no user with this id');
        }
    }

    public function logout(Request $request)
    {
        if (!$request->header('token')) {
            return $this->returnError(201, 'You Din not enter your token !');
        }
        if ($this->userAuthentication->logout($request->header('token'))['status'] == true) {
            return $this->returnSuccessMessage('user logged out successfully !');
        } else {
            return $this->returnError(201, 'user did not log out !');
        }
    }

    public function updateProfile(Request $request)
    {
        if ($this->validToken($request) == 1) {
            // return $this->returnError(201,'Please Active Your Account');
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:5|max:255',
                'phone' => 'required|string|min:9|unique:users,phone,' . $this->user->id,
                'address' => 'required|string|min:4',
                'id_number' => 'required|string|min:10|unique:users,id_number,' . $this->user->id,
                'birthday' => 'required|date|date_format:Y-m-d|before:today',
                'email' => 'required|string|email|min:5|max:255|unique:users,email,' . $this->user->id,
                'password' => 'string|min:8',
                'gender' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->returnValidationError(422, $validator);
            } else {

                $dataUpdated = [
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'id_number' => $request->id_number,
                    'gender' => $request->gender,
                    'birthday' => $request->birthday,
                    'email' => $request->email,
                    'address' => $request->address

                ];
                if ($request->has('password') && $request->get('password')) {
                    $dataUpdated['password'] = $request->password;
                }
                User::find($this->user->id)->update($dataUpdated);

                return $this->returnData(['user'], [$this->user], 'User Details updated Successfully');

            }

        } elseif ($this->validToken($request) == 0) {
            return $this->returnError(201, 'Unauthorized User');
        } else {
            return $this->returnError(201, 'Please Active Your Account');
        }


    }

    public function forgetPass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|exists:users',
        ]);
        if ($validator->fails()) {
            return $this->returnValidationError(422, $validator);
        } else {
            $user = User::where('email', $request->email)->get();
            if (count($user) > 0) {
                $objDemo = new \stdClass();
                $rand = rand(00000, 99999);
                $objDemo->urlVerfiy = 'Code : ' . $rand;
                //url('api/user/verify/' . $token);
                $objDemo->name = $user[0]->name;
                Mail::to($request->email)->send(new DemoEmailApiForegtPassword($objDemo));
                $user[0]->update(['validation_code' => $rand]);
                return $this->returnData(['validation_code'], [$rand], 'Check Your Email And Enter the code');
            } else {
                return $this->returnError(200, 'Email Not Found');

            }
        }
    }

    public function resetPass(Request $request)
    {
        $token = Str::random(60);
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'validation_code' => 'required',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return $this->returnValidationError(422, $validator);
        } else {
            $user = User::where('email', $request->email)->get();
            if (count($user) > 0) {
                if ($user[0]->validation_code == $request->validation_code) {
                    $rand = rand(00000, 99999);
                    $dataUpdated = [
                        'validation_code' => $rand,
                    ];
                    if ($request->has('password') && $request->get('password')) {
                        $dataUpdated['password'] = Hash::make($request->password);
                        $credentials = [
                            'email' => $user[0]->email,
                            'password' => $request->password
                        ];
                        $token = Auth::guard('api')->attempt($credentials);
                        $user[0]->update([
                            'remember_token' => $token
                        ]);
                    }
                    User::find($user[0]->id)->update($dataUpdated);
                    return $this->returnSuccessMessage('Your Account has been verified successfully', 200);
                } else {

                    return $this->returnError(201, 'Code is not correct please check your email ');
                }
            } else {
                return $this->returnError(201, 'Email Not Found');

            }
        }
    }
}
