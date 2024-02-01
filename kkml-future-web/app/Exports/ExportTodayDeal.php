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
use Carbon\Carbon;

class ExportTodayDeal implements FromCollection, WithStyles, WithHeadings
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
            'id', 'user_id', 'guest_id', 'seller_id', 'shipping_address', 'delivery_status', 'payment_type', 'manual_payment', 'manual_payment_data', 'payment_status', 'payment_details', 'grand_total', 'coupon_discount', 'code', 'date', 'viewed', 'delivery_viewed', 'payment_status_viewed', 'commission_calculated', 'commission_to', 'created_at', 'updated_at',
        ];
    }
    public function collection()
    {
        return Order::whereDate('created_at', Carbon::today())->get();
    }
}
