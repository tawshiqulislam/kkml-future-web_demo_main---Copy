<?php

namespace App\Http\Controllers;

use App\Service;
use App\ServiceOrder;
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

class ServiceOrderController extends Controller
{
    public function service_order (Request $request)
    {

        $date = $request->date;
        $sort_search = null;
        $orders = ServiceOrder::orderBy('code', 'desc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        if ($date != null) {
            $orders = $orders->whereDate('created_at', '>=', date('Y-m-d', strtotime(explode("to", $date)[0])))->whereDate('created_at', '<=', date('Y-m-d', strtotime(explode("to", $date)[1])));
        }
        $orders = $orders->paginate(15);
        return view('backend.service.order.index', compact('orders', 'sort_search', 'date'));
    }

    public function service_order_accept($id)
    {
        $order = ServiceOrder::findOrFail($id);
        $order->status = 'Completed';
        $order->save();
        return back();
    }

    public function service_order_reject($id)
    {
        $order = ServiceOrder::findOrFail($id);
        $order->status = 'Rejected';
        $order->save();
        return back();
    }
}
