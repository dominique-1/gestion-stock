<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Product::with('category')->get();
    }

    public function headings(): array
    {
        return [
            'Nom',
            'Code-barres',
            'Catégorie',
            'Fournisseur',
            'Prix',
            'Stock actuel',
            'Stock min',
            'Stock optimal',
            'Valeur stock'
        ];
    }

    public function map($product): array
    {
        return [
            $product->name,
            $product->barcode,
            $product->category->name ?? '',
            $product->supplier,
            $product->price,
            $product->current_stock,
            $product->stock_min,
            $product->stock_optimal,
            $product->stockValue()
        ];
    }
}
