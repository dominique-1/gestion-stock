<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ExportControllerSimple extends Controller
{
    public function index()
    {
        return view('exports.index', [
            'available_exports' => [
                'CSV' => [
                    ['name' => 'Stock complet', 'route' => 'exports.stock.csv', 'icon' => '📊', 'description' => 'Export de tous les produits et leurs stocks'],
                    ['name' => 'Mouvements', 'route' => 'exports.movements.csv', 'icon' => '📋', 'description' => 'Historique des mouvements de stock'],
                ],
                'Excel' => [
                    ['name' => 'Stock Excel', 'route' => 'exports.stock.xls', 'icon' => '📈', 'description' => 'Export Excel du stock'],
                    ['name' => 'Mouvements Excel', 'route' => 'exports.movements.xls', 'icon' => '📊', 'description' => 'Export Excel des mouvements'],
                ],
                'PDF' => [
                    ['name' => 'Inventaires', 'route' => 'exports.inventaires.pdf', 'icon' => '📄', 'description' => 'Rapport PDF des inventaires'],
                    ['name' => 'Liste produits', 'route' => 'exports.products.pdf', 'icon' => '📑', 'description' => 'Catalogue PDF des produits'],
                ],
            ],
            'stats' => [
                'total_products' => 0,
                'total_movements' => 0,
                'total_inventories' => 0,
            ]
        ]);
    }

    public function stockCsv()
    {
        $csv = "Nom,Référence,Catégorie,Stock actuel,Stock min,Stock optimal,Prix\n";
        $csv .= "Produit A,REF001,Électronique,50,10,100,299.99\n";
        $csv .= "Produit B,REF002,Informatique,25,5,50,599.99\n";
        $csv .= "Produit C,REF003,Bureau,100,20,150,149.99\n";

        $filename = 'stock_' . date('Y-m-d_H-i-s') . '.csv';
        
        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    public function movementsCsv()
    {
        $csv = "Date,Produit,Type,Quantité,Utilisateur,Raison\n";
        $csv .= date('Y-m-d H:i:s') . ",Produit A,entrée,10,Admin,Réception stock\n";
        $csv .= date('Y-m-d H:i:s', strtotime('-1 day')) . ",Produit B,sortie,5,Admin,Vente\n";
        $csv .= date('Y-m-d H:i:s', strtotime('-2 days')) . ",Produit C,entrée,20,Admin,Réapprovisionnement\n";

        $filename = 'mouvements_' . date('Y-m-d_H-i-s') . '.csv';
        
        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    public function stockExcel()
    {
        $csv = "Nom,Référence,Catégorie,Stock actuel,Stock min,Stock optimal,Prix,Valeur stock\n";
        $csv .= "Produit A,REF001,Électronique,50,10,100,299.99,14999.50\n";
        $csv .= "Produit B,REF002,Informatique,25,5,50,599.99,14999.75\n";
        $csv .= "Produit C,REF003,Bureau,100,20,150,149.99,14999.00\n";

        $filename = 'stock_' . date('Y-m-d_H-i-s') . '.xls';
        
        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    public function movementsExcel()
    {
        $csv = "Date,Produit,Type,Quantité,Utilisateur,Raison\n";
        $csv .= date('Y-m-d H:i:s') . ",Produit A,entrée,10,Admin,Réception stock\n";
        $csv .= date('Y-m-d H:i:s', strtotime('-1 day')) . ",Produit B,sortie,5,Admin,Vente\n";
        $csv .= date('Y-m-d H:i:s', strtotime('-2 days')) . ",Produit C,entrée,20,Admin,Réapprovisionnement\n";

        $filename = 'mouvements_' . date('Y-m-d_H-i-s') . '.xls';
        
        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}
