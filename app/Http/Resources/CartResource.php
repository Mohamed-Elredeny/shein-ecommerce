<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $products = $this->products;
        $new_products = [];
        foreach ($products as $product) {
            $product->product->amount = $product->amount;
            $new_products [] = $product->product;
        }
        $total = 0;
        foreach ($new_products as $product) {
            $product_price = $product->price;
            $product_discount_price = $product->discount_price;
            $product_total_price = $product_price  - ($product_price * ($product_discount_price / 100));
            $product_total_price *= $product->amount;
            $total += $product_total_price;
        }

        return [
            'products' => ProductsResource::collection($new_products),
            'coupon' => $this->coupon,
            'discount' => $this->discount,
            'total' => $total,
            'total_after_discount' => $total * ($this->discount / 100),
        ];

    }
}
