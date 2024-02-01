<?php

namespace App\Exports;

use App\Category;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithBackgroundColor;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportCategory implements FromCollection, WithStyles, WithHeadings
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
            'id', 'parent_id', 'level', 'name', 'order_level', 'commision_rate', 'banner', 'icon', 'featured', 'top', 'digital', 'slug', 'meta_title', 'meta_description', 'created_at', 'updated_at',
        ];
    }

    public function collection()
    {
        return Category::all();
    }
}
