<?php

namespace App\Http\Controllers;

use App\ServiceCategory;
use Artisan;
use Auth;
use Carbon\Carbon;
use Combinations;
use CoreComponentRepository;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use Illuminate\Support\Str;
use ImageOptimizer;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class ServiceCategoryController extends Controller
{
    public function create()
    {
        $categories = ServiceCategory::all();
        return view('backend.service.category.create');
    }

    public function store(Request $request)
    {
        $serviceCategory = new ServiceCategory;
        $serviceCategory->name = $request->name;
        $serviceCategory->image = $request->image;
        $tags = array();
        if ($request->tags[0] != null) {
            foreach (json_decode($request->tags[0]) as $key => $tag) {
                array_push($tags, $tag->value);
            }
        }
        $serviceCategory->tags = implode(',', $tags);
        $serviceCategory->description = $request->description;

        if($serviceCategory->save())
        {
            Session::flash('success','Service Category Added Successfully');
            return redirect()->route('service_category.all');
        }
        else{
            Session::flash('error','Something went wrong');
            return redirect()->route('service_category.all');
        }
    }

    public function index(Request $request)
    {
        $categories = ServiceCategory::orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $categories = $categories->where('name', 'like', '%'.$sort_search.'%');
        }
        $categories = $categories->paginate(15);
        return view('backend.service.category.index', compact('categories'));
    }

    public function edit(Request $request)
    {
        if($category = ServiceCategory::findOrFail($request->id)){
            return view('backend.service.category.edit', compact('category'));
        }
        else
        {
            Session::flash('error','Something went wrong');
            return redirect()->route('service_category.all');
        }
        
    }

    public function update(Request $request, $id)
    {
        $serviceCategory = ServiceCategory::findOrFail($id);
        $serviceCategory->image = $request->image;
        $serviceCategory->name = $request->name;
        $serviceCategory->description = $request->description;
        $serviceCategory->save();
        flash(translate('Category has been updated successfully'))->success();
        return back();
    }
    public function delete($id)
    {
        $serviceCategory = ServiceCategory::findOrFail($id);
        if (ServiceCategory::destroy($id)) {
            Session::flash('success','Service has been deleted successfully');
            return redirect()->route('service_category.all');
        } else {
            Session::flash('error','Something went wrong');
            return redirect()->route('service_category.all');
        }
    }
}
