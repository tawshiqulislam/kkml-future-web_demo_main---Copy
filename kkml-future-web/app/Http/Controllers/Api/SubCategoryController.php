<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoryCollection;
use App\Http\Resources\SubCategoryCollection;
use App\Models\Category;

class SubCategoryController extends Controller
{
    public function index($id)
    {
        return new CategoryCollection(Category::where('parent_id', $id)->get());
    }


}
