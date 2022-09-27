<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function profile(Request $request)
    {
        if ($request->id) {
            $id = $request->id;
            $user = User::find($id);
        } else {
            $user = Auth::user();
        }
        if (!$user) {
            return redirect()->back()->with('errors', 'Invalid Page');
        }

        return view('site.profile', ['user' => $user]);
    }

    public function update(Request $request)
    {
        $id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $id,
            'phone' => 'required|unique:users,phone,' . $id,
            'password' => 'nullable|min:6'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('errors', $validator->getMessageBag());
        } else {
            $record = User::find($id);
            if ($record) {
                $data = [];
                if ($request->name) {
                    $data['name'] = $request->name;
                } else {
                    $data['name'] = $record->name;
                }

                if ($request->email) {
                    $data['email'] = $request->email;
                } else {
                    $data['email'] = $record->email;
                }

                if ($request->phone) {
                    $data['phone'] = $request->phone;
                } else {
                    $data['phone'] = $record->phone;
                }

                if ($request->password) {
                    $data['password'] = Hash::make($request->password);
                } else {
                    $data['password'] = $record->password;
                }
                if ($request->image) {
                    $image = $this->uploadImage($request, 'image', 'users');
                    $data = [
                        'image' => asset('assets/images/users/' . $image),
                    ];
                }

                $this->base->update($data, $id);
                $record = User::find($id);
                return redirect()->back()->with('success', 'updated Successfully');
//                return $this->returnData([$this->record], [$record]);
            } else {
                return $this->returnError(201, $this->record . ' Not Found With This ID ');
            }
        }

    }

}
