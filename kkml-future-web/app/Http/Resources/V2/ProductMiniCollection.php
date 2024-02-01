<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductMiniCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                $flash_deal = flash_deal_product($data->id);
                if ($flash_deal == null) {
                    $discount = (double)$data->discount;
                    $discount_type = $data->discount_type;
                } else {
                    $discount = (double)$flash_deal->discount;
                    $discount_type = $flash_deal->discount_type;
                }
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'thumbnail_image' => api_asset($data->thumbnail_img),
                    'base_price' => format_price(homeBasePrice($data->id)),
                    'base_discounted_price' => (double)homeDiscountedBasePrice($data->id),
                    'discount' => $discount,
                    'discount_type' => $discount_type,
                    'rating' => (double)$data->rating,
                    'sales' => (integer)$data->num_of_sale,
                    'links' => [
                        'details' => route('products.show', $data->id),
                    ]
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
