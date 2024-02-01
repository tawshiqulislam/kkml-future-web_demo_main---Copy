<?php

namespace App\Http\Controllers;

use App\Service;
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

class ServiceController extends Controller
{
    public function create()
    {
        $categories = ServiceCategory::all();
        return view('backend.service.create', compact('categories'));
    }

    public function index(Request $request)
    {
        $col_name = null;
        $query = null;
        $seller_id = null;
        $sort_search = null;
        $services = Service::orderBy('created_at', 'desc');
        if ($request->has('user_id') && $request->user_id != null) {
            $services = $services->where('user_id', $request->user_id);
            $seller_id = $request->user_id;
        }
        if ($request->search != null) {
            $services = $services
                ->where('name', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }
        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $services = $services->orderBy($col_name, $query);
            $sort_type = $request->type;
        }

        $services = $services->paginate(15);
        $type = 'All';

        return view('backend.service.index', compact('services', 'type', 'col_name', 'query', 'seller_id', 'sort_search'));
    }

    public function store(Request $request)
    {
        $service = new Service;
        $service->name = $request->name;
        $service->added_by = $request->added_by;
        if (Auth::user()->user_type == 'seller') {
            $service->user_id = Auth::user()->id;
        } else {
            $service->user_id = \App\User::where('user_type', 'admin')->first()->id;
        }
        $service->thumbnail_img = $request->thumbnail_img;
        $tags = array();
        if ($request->tags[0] != null) {
            foreach (json_decode($request->tags[0]) as $key => $tag) {
                array_push($tags, $tag->value);
            }
        }
        $service->tags = implode(',', $tags);

        $service->description = $request->description;
        $service->address = $request->address;
        $service->video_provider = $request->video_provider;
        $service->video_link = $request->video_link;
        $service->category_id = $request->service_category_id;

        $service->meta_title = $request->meta_title;
        $service->meta_description = $request->meta_description;

        if ($request->has('meta_img')) {
            $service->meta_img = $request->meta_img;
        } else {
            $service->meta_img = $service->thumbnail_img;
        }

        if ($service->meta_title == null) {
            $service->meta_title = $service->name;
        }

        if ($service->meta_description == null) {
            $service->meta_description = strip_tags($service->description);
        }

        if ($service->meta_img == null) {
            $service->meta_img = $service->thumbnail_img;
        }

        if ($request->hasFile('pdf')) {
            $service->pdf = $request->pdf->store('uploads/products/pdf');
        }
        $service->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)) . '-' . Str::random(5);
        
        $choice_options = array();

        $service->published = 1;
        if ($request->button == 'unpublish') {
            $service->published = 0;
        }

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_' . $no;

                $item['attribute_id'] = $no;

                $data = array();
                foreach (json_decode($request[$str][0]) as $key => $eachValue) {
                    array_push($data, $eachValue->value);
                }

                $item['values'] = $data;
                array_push($choice_options, $item);
            }
        }

        if (!empty($request->choice_no)) {
            $service->attributes = json_encode($request->choice_no);
        } else {
            $service->attributes = json_encode(array());
        }

        $service->choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);
        if($service->save()){
            Session::flash('success','Service Added');
            return redirect()->route('service.all');
        }
        else
        {
            Session::flash('error','Something went wrong');
            return redirect()->route('service.all');
        }
    }

    public function updatePublished(Request $request)
    {
        $service = Service::findOrFail($request->id);
        // $service->published = $request->status;

        if($service->published == 1){
            $service->published = 0;
        }
        else{
            $service->published = 1;
        }

        $service->save();
        return 1;
    }

    public function edit(Request $request)
    {
        $service = Service::findOrFail($request->id);
        $categories = ServiceCategory::all();
        $current_category = ServiceCategory::findOrFail($service->category_id);
        return view('backend.service.edit', compact('service','categories','current_category'));
    }

    public function update(Request $request)
    {
        $service = Service::findOrFail($request->id);
        $service->thumbnail_img = $request->thumbnail_img;
        $service->name = $request->name;
        
        $service->video_provider = $request->video_provider;
        $service->video_link = $request->video_link;
        $service->description = $request->description;
        $service->address = $request->address;
        $service->category_id = $request->service_category_id;

        $service->meta_title = $request->meta_title;
        $service->meta_description = $request->meta_description;

        if ($request->has('meta_img')) {
            $service->meta_img = $request->meta_img;
        } else {
            $service->meta_img = $service->thumbnail_img;
        }

        if ($service->meta_title == null) {
            $service->meta_title = $service->name;
        }

        if ($service->meta_description == null) {
            $service->meta_description = strip_tags($service->description);
        }

        if ($service->meta_img == null) {
            $service->meta_img = $service->thumbnail_img;
        }

        if ($request->hasFile('pdf')) {
            $service->pdf = $request->pdf->store('uploads/products/pdf');
        }
        $service->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)) . '-' . Str::random(5);

        if($service->save()){
            Session::flash('success','Service Updated');
            return redirect()->route('service.all');
        }
        else
        {
            Session::flash('error','Something went wrong');
            return redirect()->route('service.all');
        }

    }
    public function delete($id)
    {
        $service = Service::findOrFail($id);
        if (Service::destroy($id)) {

            flash(translate('Service has been deleted successfully'))->success();
            if(file_exists($service->thumbnail_img)){
                File::delete($service->thumbnail_img);
            }
            Session::flash('success','Service Deleted');
            return redirect()->route('service.all');
        } else {
            Session::flash('error','Something went wrong');
            return redirect()->route('service.all');
        }
    }
}
