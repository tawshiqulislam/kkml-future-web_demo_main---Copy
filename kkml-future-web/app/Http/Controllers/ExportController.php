<?php

namespace App\Http\Controllers;

use App\Exports\ExportBrand;
use App\Exports\ExportCategory;
use App\Exports\ExportOrder;
use App\Exports\ExportProduct;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportTodayDeal;

class ExportController extends Controller
{
    public function export_todays_sale()
    {
        return Excel::download(new ExportTodayDeal, 'todays_deal_'. now() .'.xlsx');
    }

    public function export_brand()
    {
        return Excel::download(new ExportBrand, 'brand_'. now() .'.xlsx');
    }

    public function export_category()
    {
        return Excel::download(new ExportCategory, 'category_'. now() .'.xlsx');
    }

    public function export_order()
    {
        return Excel::download(new ExportOrder, 'order_'. now() .'.xlsx');
    }

    public function export_product()
    {
        return Excel::download(new ExportProduct, 'product_'. now() .'.xlsx');
    }
}
