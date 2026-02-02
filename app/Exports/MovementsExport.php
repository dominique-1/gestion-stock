<?php

namespace App\Exports;

use App\Models\StockMovement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class MovementsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = StockMovement::with('product', 'user');

        if ($this->request->filled('from')) {
            $query->whereDate('moved_at', '>=', $this->request->from);
        }
        if ($this->request->filled('to')) {
            $query->whereDate('moved_at', '<=', $this->request->to);
        }

        return $query->orderBy('moved_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Produit',
            'Type',
            'Raison',
            'Quantité',
            'Utilisateur',
            'Note'
        ];
    }

    public function map($movement): array
    {
        return [
            $movement->moved_at->format('Y-m-d H:i'),
            $movement->product->name,
            $movement->type,
            $movement->reason,
            $movement->quantity,
            $movement->user->name,
            $movement->note
        ];
    }
}
