<?php

namespace App\Services;

use App\Models\SmartAlert;
use App\Models\Product;
use App\Models\StockMovement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DatabaseSmartAlertService
{
    private $config = [
        'stock_min_threshold' => 10,
        'overstock_threshold' => 100,
        'expiry_days_threshold' => 30,
        'prediction_risk_days' => 7,
        'movement_anomaly_threshold' => 50, // mouvements par semaine
    ];

    public function generateAllAlerts()
    {
        $alertsGenerated = 0;
        
        // Nettoyer les anciennes alertes de même type pour éviter les doublons
        $this->cleanupDuplicateAlerts();
        
        // Générer les différents types d'alertes
        $alertsGenerated += $this->generateStockMinAlerts();
        $alertsGenerated += $this->generateOverstockAlerts();
        $alertsGenerated += $this->generateExpiryAlerts();
        $alertsGenerated += $this->generatePredictionRiskAlerts();
        $alertsGenerated += $this->generateMovementAnomalyAlerts();
        
        Log::info("Smart Alerts generated: {$alertsGenerated} alerts");
        
        return $alertsGenerated;
    }

    private function generateStockMinAlerts()
    {
        $alertsGenerated = 0;
        
        $products = Product::with('category')->get();
        
        foreach ($products as $product) {
            if ($product->current_stock <= $product->stock_min) {
                $existingAlert = SmartAlert::where('product_id', $product->id)
                    ->where('type', 'stock_min')
                    ->where('is_read', false)
                    ->first();
                
                if (!$existingAlert) {
                    $level = $product->current_stock <= ($product->stock_min / 2) ? 'critical' : 'warning';
                    
                    $alert = SmartAlert::create([
                        'alert_id' => SmartAlert::generateAlertId('stock_min', $product->id),
                        'type' => 'stock_min',
                        'level' => $level,
                        'title' => 'Stock minimum atteint',
                        'message' => "Le produit '{$product->name}' a atteint son stock minimum ({$product->current_stock}/{$product->stock_min})",
                        'product_id' => $product->id,
                        'data' => [
                            'current_stock' => $product->current_stock,
                            'min_stock' => $product->stock_min,
                            'shortage_percentage' => round((1 - $product->current_stock / $product->stock_min) * 100, 1),
                            'category' => $product->category->name ?? 'Non catégorisé',
                            'reference' => $product->reference ?? 'N/A',
                        ],
                    ]);
                    
                    $alertsGenerated++;
                }
            }
        }
        
        return $alertsGenerated;
    }

    private function generateOverstockAlerts()
    {
        $alertsGenerated = 0;
        
        $products = Product::get();
        
        foreach ($products as $product) {
            if ($product->current_stock >= $this->config['overstock_threshold']) {
                $existingAlert = SmartAlert::where('product_id', $product->id)
                    ->where('type', 'overstock')
                    ->where('is_read', false)
                    ->first();
                
                if (!$existingAlert) {
                    $alert = SmartAlert::create([
                        'alert_id' => SmartAlert::generateAlertId('overstock', $product->id),
                        'type' => 'overstock',
                        'level' => 'warning',
                        'title' => 'Produit en surstock',
                        'message' => "Le produit '{$product->name}' est en surstock ({$product->current_stock} unités)",
                        'product_id' => $product->id,
                        'data' => [
                            'current_stock' => $product->current_stock,
                            'overstock_amount' => $product->current_stock - $this->config['overstock_threshold'],
                            'suggested_action' => 'Promotion ou vente groupée recommandée',
                            'reference' => $product->reference ?? 'N/A',
                        ],
                    ]);
                    
                    $alertsGenerated++;
                }
            }
        }
        
        return $alertsGenerated;
    }

    private function generateExpiryAlerts()
    {
        $alertsGenerated = 0;
        
        $products = Product::whereNotNull('expires_at')->get();
        
        foreach ($products as $product) {
            $daysToExpiry = $product->expires_at->diffInDays(now());
            
            if ($daysToExpiry <= $this->config['expiry_days_threshold']) {
                $existingAlert = SmartAlert::where('product_id', $product->id)
                    ->where('type', 'expiry')
                    ->where('is_read', false)
                    ->first();
                
                if (!$existingAlert) {
                    $level = $daysToExpiry <= 7 ? 'critical' : 'warning';
                    
                    $alert = SmartAlert::create([
                        'alert_id' => SmartAlert::generateAlertId('expiry', $product->id),
                        'type' => 'expiry',
                        'level' => $level,
                        'title' => 'Expiration proche',
                        'message' => "Le produit '{$product->name}' expire dans {$daysToExpiry} jours",
                        'product_id' => $product->id,
                        'data' => [
                            'expires_at' => $product->expires_at->format('Y-m-d'),
                            'days_to_expiry' => $daysToExpiry,
                            'urgency' => $level === 'critical' ? 'Urgent' : 'Modéré',
                            'reference' => $product->reference ?? 'N/A',
                        ],
                    ]);
                    
                    $alertsGenerated++;
                }
            }
        }
        
        return $alertsGenerated;
    }

    private function generatePredictionRiskAlerts()
    {
        $alertsGenerated = 0;
        
        // Simuler des prédictions basées sur les tendances des mouvements
        $products = Product::get();
        
        foreach ($products as $product) {
            // Calculer la tendance des mouvements récents
            $recentMovements = StockMovement::where('product_id', $product->id)
                ->where('created_at', '>=', now()->subDays(30))
                ->get();
            
            if ($recentMovements->count() >= 5) {
                // Calculer la tendance de stock
                $stockTrend = $this->calculateStockTrend($product, $recentMovements);
                
                if ($stockTrend['risk_level'] === 'high') {
                    $existingAlert = SmartAlert::where('product_id', $product->id)
                        ->where('type', 'prediction_risk')
                        ->where('is_read', false)
                        ->first();
                    
                    if (!$existingAlert) {
                        $alert = SmartAlert::create([
                            'alert_id' => SmartAlert::generateAlertId('prediction_risk', $product->id),
                            'type' => 'prediction_risk',
                            'level' => 'critical',
                            'title' => 'Risque de rupture selon prédiction',
                            'message' => "Risque de rupture de stock détecté pour '{$product->name}' dans les prochains jours",
                            'product_id' => $product->id,
                            'data' => [
                                'prediction_confidence' => $stockTrend['confidence'],
                                'risk_days' => $this->config['prediction_risk_days'],
                                'recommended_action' => 'Réapprovisionnement immédiat recommandé',
                                'trend_slope' => $stockTrend['slope'],
                                'reference' => $product->reference ?? 'N/A',
                            ],
                        ]);
                        
                        $alertsGenerated++;
                    }
                }
            }
        }
        
        return $alertsGenerated;
    }

    private function generateMovementAnomalyAlerts()
    {
        $alertsGenerated = 0;
        
        // Détecter les anomalies dans les mouvements de stock
        $recentMovements = StockMovement::where('created_at', '>=', now()->subDays(7))->get();
        
        if ($recentMovements->count() > $this->config['movement_anomaly_threshold']) {
            $existingAlert = SmartAlert::where('type', 'movement_anomaly')
                ->where('is_read', false)
                ->first();
            
            if (!$existingAlert) {
                $alert = SmartAlert::create([
                    'alert_id' => SmartAlert::generateAlertId('movement_anomaly'),
                    'type' => 'movement_anomaly',
                    'level' => 'warning',
                    'title' => 'Activité anormale détectée',
                    'message' => "Plus de {$this->config['movement_anomaly_threshold']} mouvements de stock enregistrés cette semaine",
                    'product_id' => null,
                    'data' => [
                        'movement_count' => $recentMovements->count(),
                        'period' => '7 derniers jours',
                        'analysis' => 'Activité inhabituelle qui mérite une attention',
                        'top_products' => $this->getTopMoverProducts($recentMovements),
                    ],
                ]);
                
                $alertsGenerated++;
            }
        }
        
        return $alertsGenerated;
    }

    private function calculateStockTrend($product, $movements)
    {
        $stockChanges = [];
        $currentStock = $product->current_stock;
        
        foreach ($movements as $movement) {
            if ($movement->type === 'out') {
                $currentStock -= $movement->quantity;
            } else {
                $currentStock += $movement->quantity;
            }
            $stockChanges[] = $currentStock;
        }
        
        if (count($stockChanges) < 2) {
            return ['risk_level' => 'low', 'confidence' => 0];
        }
        
        // Calculer la tendance (régression linéaire simple)
        $n = count($stockChanges);
        $sumX = array_sum(range(0, $n - 1));
        $sumY = array_sum($stockChanges);
        $sumXY = 0;
        
        for ($i = 0; $i < $n; $i++) {
            $sumXY += $i * $stockChanges[$i];
        }
        
        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * array_sum(array_map(function($x) { return $x * $x; }, range(0, $n - 1))) - $sumX * $sumX);
        
        $riskLevel = 'low';
        if ($slope < -2) {
            $riskLevel = 'high';
        } elseif ($slope < -1) {
            $riskLevel = 'medium';
        }
        
        $confidence = min(95, max(70, 100 - abs($slope) * 10));
        
        return [
            'risk_level' => $riskLevel,
            'confidence' => $confidence,
            'slope' => $slope,
        ];
    }

    private function getTopMoverProducts($movements)
    {
        $productMovements = [];
        
        foreach ($movements as $movement) {
            if (!isset($productMovements[$movement->product_id])) {
                $productMovements[$movement->product_id] = [
                    'product_id' => $movement->product_id,
                    'product_name' => $movement->product->name ?? 'Unknown',
                    'total_movements' => 0,
                ];
            }
            $productMovements[$movement->product_id]['total_movements']++;
        }
        
        // Trier par nombre de mouvements et prendre les 5 premiers
        uasort($productMovements, function($a, $b) {
            return $b['total_movements'] - $a['total_movements'];
        });
        
        return array_slice($productMovements, 0, 5);
    }

    private function cleanupDuplicateAlerts()
    {
        // Nettoyer les alertes de même type pour le même produit qui sont déjà lues
        $duplicateAlerts = SmartAlert::selectRaw('product_id, type, MAX(created_at) as latest_created_at')
            ->where('is_read', true)
            ->whereNotNull('product_id')
            ->groupBy(['product_id', 'type'])
            ->havingRaw('COUNT(*) > 1')
            ->get();
        
        foreach ($duplicateAlerts as $duplicate) {
            // Supprimer les anciennes alertes lues, garder la plus récente
            SmartAlert::where('product_id', $duplicate->product_id)
                ->where('type', $duplicate->type)
                ->where('is_read', true)
                ->where('created_at', '<', $duplicate->latest_created_at)
                ->delete();
        }
    }

    public function sendEmailAlerts($alerts = null)
    {
        if (!$alerts) {
            $alerts = SmartAlert::unread()->get();
        }
        
        $sentCount = 0;
        
        foreach ($alerts as $alert) {
            try {
                // Envoyer l'email
                $this->sendAlertEmail($alert);
                
                // Marquer comme envoyé
                $alert->markEmailSent();
                $sentCount++;
                
            } catch (\Exception $e) {
                Log::error('Failed to send alert email', [
                    'alert_id' => $alert->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return $sentCount;
    }

    private function sendAlertEmail($alert)
    {
        // Implémenter l'envoi d'email réel
        $emailData = [
            'to' => config('alerts.email_recipient', 'admin@stockapp.com'),
            'subject' => "[{$alert->level}] {$alert->title}",
            'alert' => $alert,
        ];
        
        // Utiliser le système d'email Laravel
        // Mail::to($emailData['to'])->send(new SmartAlertEmail($alert));
        
        // Pour l'instant, on simule l'envoi
        Log::info('Alert email sent', [
            'alert_id' => $alert->id,
            'type' => $alert->type,
            'level' => $alert->level,
            'sent_at' => now()->format('Y-m-d H:i:s')
        ]);
        
        return $emailData;
    }

    public function getDashboardSummary()
    {
        $alerts = SmartAlert::with('product')->get();
        
        return [
            'total' => $alerts->count(),
            'critical' => $alerts->where('level', 'critical')->count(),
            'warning' => $alerts->where('level', 'warning')->count(),
            'info' => $alerts->where('level', 'info')->count(),
            'unread' => $alerts->where('is_read', false)->count(),
            'by_type' => [
                'stock_min' => $alerts->where('type', 'stock_min')->count(),
                'overstock' => $alerts->where('type', 'overstock')->count(),
                'expiry' => $alerts->where('type', 'expiry')->count(),
                'prediction_risk' => $alerts->where('type', 'prediction_risk')->count(),
                'movement_anomaly' => $alerts->where('type', 'movement_anomaly')->count(),
            ],
            'recent' => $alerts->sortByDesc('created_at')->take(5),
            'performance' => $this->calculatePerformanceMetrics($alerts),
        ];
    }

    private function calculatePerformanceMetrics($alerts)
    {
        $total = $alerts->count();
        $read = $alerts->where('is_read', true)->count();
        $dismissed = $alerts->where('dismissed', true)->count();
        $emailSent = $alerts->where('email_sent', true)->count();
        
        return [
            'read_rate' => $total > 0 ? round(($read / $total) * 100, 1) : 0,
            'dismissal_rate' => $total > 0 ? round(($dismissed / $total) * 100, 1) : 0,
            'email_delivery_rate' => $total > 0 ? round(($emailSent / $total) * 100, 1) : 0,
            'avg_resolution_time' => $this->calculateAverageResolutionTime($alerts),
        ];
    }

    private function calculateAverageResolutionTime($alerts)
    {
        $resolvedAlerts = $alerts->whereNotNull('read_at');
        
        if ($resolvedAlerts->isEmpty()) {
            return 0;
        }
        
        $totalMinutes = $resolvedAlerts->sum(function($alert) {
            return $alert->created_at->diffInMinutes($alert->read_at);
        });
        
        return round($totalMinutes / $resolvedAlerts->count(), 1);
    }
}
