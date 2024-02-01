<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithBackgroundColor;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Order;
use App\Product;
use Carbon\Carbon;

class ExportProduct implements FromCollection, WithStyles, WithHeadings
{
    public function styles(Worksheet $sheet)
    {
        return [
            '1'  => ['font' => ['size' => 14]],
        ];
    }

    public function headings(): array
    {
        return [
            'id', 'name', 'added_by', 'user_id', 'category_id', 'subcategory_id', 'subsubcategory_id', 'brand_id', 'photos', 'thumbnail_img', 'video_provider', 'video_link', 'tags', 'description', 'unit_price', 'purchase_price', 'variant_product', 'attributes', 'choice_options', 'colors', 'variations', 'todays_deal', 'published', 'stock_visibility_state', 'cash_on_delivery', 'featured', 'seller_featured', 'current_stock', 'unit', 'min_qty', 'low_stock_quantity', 'discount', 'discount_type', 'tax', 'tax_type', 'shipping_type', 'shipping_cost', 'is_quantity_multiplied', 'est_shipping_days', 'num_of_sale', 'meta_title', 'meta_description', 'meta_img', 'pdf', 'slug', 'refundable', 'earn_point', 'rating', 'barcode', 'digital', 'file_name', 'file_path', 'created_at', 'updated_at',
        ];
    }
    public function collection()
    {
        return Product::all();
    }
}
