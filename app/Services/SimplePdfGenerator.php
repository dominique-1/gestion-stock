<?php

namespace App\Services;

class SimplePdfGenerator
{
    public static function generateFromHtml($html, $filename = 'document.pdf', $download = false)
    {
        // Créer un HTML optimisé pour l'impression PDF
        $printableHtml = self::createPrintableHtml($html, $filename);
        
        return response($printableHtml, 200, [
            'Content-Type' => 'text/html; charset=utf-8',
            'Content-Disposition' => 'inline; filename="' . str_replace('.pdf', '.html', $filename) . '"'
        ]);
    }
    
    public static function generatePdfFromView($view, $data = [], $filename = 'document.pdf', $download = false)
    {
        $html = view($view, $data)->render();
        return self::generateFromHtml($html, $filename, $download);
    }
    
    private static function createPrintableHtml($content, $filename)
    {
        $timestamp = date('d/m/Y H:i:s');
        
        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>' . $filename . '</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm;
            @bottom-center {
                content: "Page " counter(page) " / " counter(pages);
                font-size: 10px;
                color: #666;
            }
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #333;
            background: white;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        
        .header .info {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }
        
        .content {
            margin: 20px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 11px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        
        .summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border: 1px solid #dee2e6;
        }
        
        .summary h3 {
            margin-top: 0;
            font-size: 14px;
        }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        
        .stock-low { color: #dc3545; font-weight: bold; }
        .stock-optimal { color: #28a745; }
        .stock-high { color: #ffc107; }
        
        .print-button {
            position: fixed;
            top: 10px;
            right: 10px;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .print-button:hover {
            background: #0056b3;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        @media print {
            .print-button { 
                display: none !important; 
            }
            
            body { 
                margin: 0; 
                padding: 10px;
            }
            
            .header {
                margin-bottom: 20px;
            }
            
            .content {
                margin: 10px 0;
            }
            
            table {
                page-break-inside: auto;
            }
            
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            
            td {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">🖨️ Imprimer en PDF</button>
    
    <div class="header">
        <h1>Système de Gestion de Stock</h1>
        <div class="info">
            Document généré le ' . $timestamp . ' | ' . $filename . '
        </div>
    </div>
    
    <div class="content">
        ' . $content . '
    </div>
    
    <div class="footer">
        <p>© Système de Gestion de Stock - Document généré automatiquement</p>
    </div>
    
    <script>
        // Auto-impression optionnelle
        if (window.location.search.includes("auto_print=1")) {
            setTimeout(() => {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>';
    }
}
