<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\SliderCollection;
use App\Models\Category;

class SliderController extends Controller
{
    public function index()
    {
        $data = collect();
        $links = json_decode(get_setting('home_slider_links'), true);
        foreach (json_decode(get_setting('home_slider_images'), true) as $key => $item) {
            $path = parse_url($links[$key], PHP_URL_PATH);
            $pathFragments = explode('/', $path);
            $end = end($pathFragments);

            $category = Category::where('slug', $end)->first();

            $data->push([
                'image' => api_asset($item),
                'has_category' => (bool)$category,
                'category_id' => $category->id ?? null,
                'link' => $links[$key]
            ]);
        }
        return new SliderCollection($data);
    }
}
