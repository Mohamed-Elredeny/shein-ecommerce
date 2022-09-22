<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductsResource;
use App\Http\Traits\GeneralTrait;
use App\Interfaces\BaseRepositoryInterface;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\UserLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    use  GeneralTrait;

    public $model;

    public function __construct(BaseRepositoryInterface $base)
    {
        $this->base = $base;
        $this->base->model('Product');
        $this->records = 'products';
        $this->record = 'products';
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
    public function index($keys = [])
    {
        if (isset($this->device) && !$this->device) {
            return $this->returnData([$this->records], [$this->base->index($keys)]);
        }
        return $this->returnData([$this->records], [ProductsResource::collection($this->base->index($keys))]);
    }

    public function wishlist(Request $request, $action)
    {
        $token = $request->header('token');
        $exist = User::where('remember_token', $token)->first();
        $exist_product = Product::find($request->product_id);
        if (!$exist_product) {
            return $this->returnError(200, 'Product not found with this id');
        }
        $exist_like = UserLike::where('user_id', $exist->id)->where('product_id', $request->product_id)->first();
        switch ($action) {
            case 'add':
                if ($exist_like) {
                    return $this->returnError(200, 'You Added This Product Before');
                } else {
                    UserLike::create([
                        'user_id' => $exist->id,
                        'product_id' => $request->product_id
                    ]);
                    return $this->returnSuccessMessage('You Added This Product successfully', 200);
                }
            case 'remove':
                if ($exist_like) {
                    $exist_like->delete();
                    return $this->returnSuccessMessage('You Deleted This Product successfully', 200);
                } else {
                    return $this->returnError(200, 'You Dont have this product in your wishlist');
                }
        }
        return $request->product_id;
    }

    public function show($id)
    {
        if (isset($this->device) && !$this->device) {
            return $this->returnData([$this->records], [$this->base->show($id)]);
        }
        return $this->returnData([$this->records], [new ProductsResource($this->base->show($id))]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|min:2',
            'price' => 'required',
            'details' => 'required',
            'images' => 'required'

        ]);
        if ($validator->fails()) {
            return $this->returnValidationError(422, $validator);
        } else {
            if ($request->images) {
                $image = $this->uploadImages($request, 'products');
            } else {
                $image = '';
            }
            if ($request->sub_category_id) {
                $model_type = 'subcategory';
                $model_id = $request->sub_category_id;
            } else {
                $model_type = 'category';
                $model_id = $request->category_id;
            }

            $data = [
                'name' => $request->name,
                'details' => $request->details,
                'price' => $request->price,
                'images' => $image,
                'category_id'=>$request->category_id
            ];

            return $this->returnData([$this->record], [$this->base->store($data)], '');
        }

    }

    public function update(Request $request, $id)
    {

        $record = Product::find($id);
        if ($record) {

            $data = [];
            if ($request->images) {
                $image = $this->uploadImages($request, 'products');
            } else {
                $image = $record->images;
            }
            if ($request->sub_category_id) {
                $model_type = 'subcategory';
                $model_id = $request->sub_category_id;
            } else {
                $model_type = 'category';
                $model_id = $request->category_id;
            }

            $data = [
                'name' => $request->name ?? $record->name,
                'details' => $request->details ?? $record->details,
                'price' => $request->price ?? $record->price,
                'images' => $image,
                'category_id'=>$request->category_id ?? $record->category_id,
            ];

            $this->base->update($data, $id);
            $record = Product::find($id);

            return $this->returnData([$this->record], [$record]);
        } else {
            return $this->returnError(201, $this->record . ' Not Found With This ID ');
        }

    }


    public function destroy($id)
    {
        if (Auth::guard('brands-api')->user()) {
            if (!$this->isBrandItems($id, $this->records)) {
                return $this->returnError(201, $this->record . 'Is Not Belong to you , so you can not edit it');
            }
        }

        if ($this->base->destroy($id)) {
            return $this->returnSuccessMessage($this->record . 'Deleted Successfully', 200);
        }
        return $this->returnError(201, $this->record . ' Not Found With This ID ');
    }
}
