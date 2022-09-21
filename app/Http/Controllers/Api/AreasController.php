<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Interfaces\BaseRepositoryInterface;
use App\Models\Area;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AreasController extends Controller
{
    use  GeneralTrait;

    public $model;

    public function __construct(BaseRepositoryInterface $base)
    {
        $this->base = $base;
        $this->base->model('Area');
        $this->records = 'areas';
        $this->record = 'area';
        $this->middleware('checkBrand', ['only' => ['store', 'update', 'destroy']]);
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
    public function index(Request $request,$keys = [])
    {
        if($request->city_id){
            $keys=[
                'city_id'=>$request->city_id
            ];
        }
        return $this->returnData([$this->records], [$this->base->index($keys)]);
    }

    public function show($id)
    {
        return $this->returnData([$this->records], [$this->base->show($id)]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->returnValidationError(422, $validator);
        } else {

            $data = [
                'name' => $request['name']
            ];

            return $this->returnData([$this->record], [$this->base->store($data), '']);
        }

    }

    public function update(Request $request, $id)
    {

        $record = Area::find($id);
        if ($record) {

            $data = [];
            if ($request->image) {
                $image = $this->uploadImage($request, 'image', 'categories');
            } else {
                $image = '';
            }

            $data = [];
            if ($request->name) {
                $data['name'] = $request->name;
            } else {
                $data['name'] = $record->name;
            }


            $this->base->update($data, $id);
            $record = Area::find($id);

            return $this->returnData([$this->record], [$record]);
        } else {
            return $this->returnError(201, $this->record . ' Not Found With This ID ');
        }

    }


    public function destroy($id)
    {
        if ($this->base->destroy($id)) {
            if (!$this->isBrandItems($id, $this->records)) {
                return $this->returnError(201, $this->record . 'Is Not Belong to you , so you can not edit it');
            }
            return $this->returnSuccessMessage($this->record . 'Deleted Successfully', 200);
        }
        return $this->returnError(201, $this->record . ' Not Found With This ID ');
    }

    public function getDevice(Request $request)
    {

    }
}
