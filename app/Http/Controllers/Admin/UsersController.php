<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Interfaces\BaseRepositoryInterface;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    use  GeneralTrait;

    public $model;

    public function __construct(BaseRepositoryInterface $base)
    {
        $this->base = $base;
        $this->base->model('User');
        $this->records = 'users';
        $this->record = 'user';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $keys = [

        ];
        $records = $this->base->index($keys);
        return view('admin.sections.users.index', compact('records'));
        //return $this->returnData([$this->records], [$collages]);
    }

    public function show($id)
    {
        $collage = $this->base->show($id);
        if ($collage) {
            $collage->products = $collage->products;
        }
        return $this->returnData([$this->records], [$collage]);
    }

    public function create()
    {
        return view('admin.sections.users.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required|min:6'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('errors', $validator->getMessageBag());
            //  return $this->returnValidationError(422, $validator);
        } else {
            if ($request->image) {
                $image = $this->uploadImage($request, 'image', 'collages');
            } else {
                $image = '';
            }

            $data = [
                'name' => $request['name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'password' => Hash::make($request['password'])
            ];
            $record = $this->base->store($data);
            return redirect()->back()->with('success', 'تمت أضافه مستخدم جديد بنجاح !');
        }

    }

    public function edit($id)
    {
        $record = User::find($id);
        return view('admin.sections.users.edit', compact('record'));
    }

    public function update(Request $request, $id)
    {
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

    public function destroy($id)
    {
        if (User::find($id)) {

            if ($this->base->destroy($id)) {
/*                return $this->returnSuccessMessage($this->record . 'Deleted Successfully', 200);*/
                return redirect()->back()->with('success', 'Deleted Successfully');
            }
        }
/*        return $this->returnError(201, $this->record . ' Not Found With This ID ');*/
        return redirect()->back()->with('errors', 'Not Found With This ID ');

    }
}
