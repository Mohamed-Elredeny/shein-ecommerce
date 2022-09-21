<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Interfaces\BaseRepositoryInterface;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserAddress extends Controller
{
    use  GeneralTrait;

    public $model;

    public function __construct(BaseRepositoryInterface $base)
    {
        $this->base = $base;
        $this->base->model('UserAddress');
        $this->records = 'userAddresses';
        $this->record = 'userAddress';
        $this->middleware(function ($request, $next) {
            if ($request->MMDevice) {
                $this->device = 'mobile';
            } else {
                $this->device = 'web';
            }
            return $next($request);
        });

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $keys = [])
    {
        $token = $request->header('token');
        $user = User::where('remember_token', $token)->first();
        $keys['user_id'] = $user->id;
        $records = $this->base->index($keys);
        foreach ($records as $record) {
            $record->area_id = $record->area;
            unset($record->area_id, $record->user_id);
        }

        return $this->returnData([$this->records], [$records]);
    }

    public function show($id)
    {
        return $this->returnData([$this->records], [$this->base->show($id)]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'area_id' => 'required',
            'street_name' => 'nullable',
            'building_number' => 'nullable',
            'floor_number' => 'nullable',
            'flat_number' => 'nullable',

        ]);
        if ($validator->fails()) {
            return $this->returnValidationError(422, $validator);
        } else {
            $token = $request->header('token');
            $user = User::where('remember_token', $token)->first();
            $data = [
                'area_id' => intval($request['area_id']),
                'user_id' => $user->id,
                'street_name' => $request['street_name'],
                'building_number' => intval($request['building_number']),
                'floor_number' => intval($request['floor_number']),
                'flat_number' => intval($request['flat_number']),
            ];
            if ($request->is_default) {
                $data['is_default'] = intval($request->is_default);
            } else {
                $data['is_default'] = 0;
            }
            $record = $this->base->store($data);
            $record->area_id = $record->area;
            unset($record->area_id, $record->user_id);
            return $this->returnData([$this->record], [$record, '']);
        }

    }

    public function update(Request $request, $id)
    {
        $token = $request->header('token');
        $user = User::where('remember_token', $token)->first();
        $record = \App\Models\UserAddress::find($id);
        if ($record) {
            if ($record->user_id != $user->id) {
                return $this->returnError(201, $this->record . 'Is Not Belong to you , so you can not edit it');
            }
            $data = [];
            if (isset($request->is_default)) {
                $data['is_default'] = $request->is_default;
            } else {
                $data['is_default'] = $record->is_default;
            }
            $this->base->update($data, $id);
            $record = \App\Models\UserAddress::find($id);

            return $this->returnData([$this->record], [$record]);
        } else {
            return $this->returnError(201, $this->record . ' Not Found With This ID ');
        }

    }


    public function destroy(Request $request, $id)
    {
        $token = $request->header('token');
        $user = User::where('remember_token', $token)->first();
        $record = \App\Models\UserAddress::find($id);
        if ($record) {
            if ($record->user_id != $user->id) {
                return $this->returnError(201, $this->record . 'Is Not Belong to you , so you can not edit it');
            }
            if ($this->base->destroy($id)) {
                return $this->returnSuccessMessage($this->record . 'Deleted Successfully', 200);
            }
        }
        return $this->returnError(201, $this->record . ' Not Found With This ID ');
    }

    public function getDevice(Request $request)
    {

    }
}
