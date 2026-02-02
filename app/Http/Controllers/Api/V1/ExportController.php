<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    public function stockCsv()
    {
        $products = Product::with('category')->get();

        $csv = "Nom,Code-barres,Catégorie,Fournisseur,Prix,Stock actuel,Stock min,Stock optimal,Valeur stock\n";
        foreach ($products as $p) {
            $csv .= '"' . $p->name . '",';
            $csv .= '"' . $p->barcode . '",';
            $csv .= '"' . ($p->category->name ?? '') . '",';
            $csv .= '"' . $p->supplier . '",';
            $csv .= $p->price . ',';
            $csv .= $p->current_stock . ',';
            $csv .= $p->stock_min . ',';
            $csv .= $p->stock_optimal . ',';
            $csv .= $p->stockValue() . "\n";
        }

        $filename = 'stock_' . now()->format('Y-m-d_H-i-s') . '.csv';
        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    public function movementsCsv(Request $request)
    {
        $query = StockMovement::with('product', 'user');

        if ($request->filled('from')) {
            $query->whereDate('moved_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('moved_at', '<=', $request->to);
        }

        $movements = $query->orderBy('moved_at', 'desc')->get();

        $csv = "Date,Produit,Type,Raison,Quantité,Utilisateur,Note\n";
        foreach ($movements as $m) {
            $csv .= $m->moved_at->format('Y-m-d H:i') . ',';
            $csv .= '"' . $m->product->name . '",';
            $csv .= $m->type . ',';
            $csv .= '"' . $m->reason . '",';
            $csv .= $m->quantity . ',';
            $csv .= '"' . $m->user->name . '",';
            $csv .= '"' . $m->note . '"' . "\n";
        }

        $filename = 'movements_' . now()->format('Y-m-d_H-i-s') . '.csv';
        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    public function inventoryPdf(Inventory $inventory)
    {
        if (!class_exists('DomPDF')) {
            return response()->json(['message' => 'PDF export not available (install barryvdh/laravel-dompdf)'], 501);
        }

        $inventory->load('user', 'lines.product');

        $html = view('exports.inventory_pdf', compact('inventory'))->render();

        $pdf = \DomPDF::loadHTML($html);
        return $pdf->download('inventory_' . $inventory->id . '.pdf');
    }

    public function productSheetPdf(Product $product)
    {
        if (!class_exists('DomPDF')) {
            return response()->json(['message' => 'PDF export not available (install barryvdh/laravel-dompdf)'], 501);
        }

        $product->load('category', 'documents', 'stockMovements');

        $html = view('exports.product_sheet_pdf', compact('product'))->render();

        $pdf = \DomPDF::loadHTML($html);
        return $pdf->download('product_' . $product->id . '_sheet.pdf');
    }
}
