<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\CategoryCollection;
use App\Http\Resources\V2\SubCategoryCollection;
use App\Models\Category;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SubCategoryController extends Controller
{
    public function index($id)
    {
        return new CategoryCollection(Category::where('parent_id', $id)->get());
    }

    public function featured_sub_category()
    {
        return new CategoryCollection(Category::where('parent_id', "!=", 0)->where('featured', 1)->get());
    }

    public function all_sub_category()
    {
        return new CategoryCollection(Category::where('parent_id', "!=", 0)->get());
    }

}
