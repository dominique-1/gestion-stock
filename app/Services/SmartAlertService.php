<?php

namespace App\Services;

use Carbon\Carbon;

class SmartAlertService
{
    private $alerts = [];
    private $config = [
        'stock_min_threshold' => 10,
        'overstock_threshold' => 100,
        'expiry_days_threshold' => 30,
        'prediction_risk_days' => 7,
    ];

    public function generateAllAlerts()
    {
        $this->alerts = [];
        
        // Récupérer les données depuis les sessions existantes
        $products = $this->getProductsFromSession();
        $movements = $this->getMovementsFromSession();
        $predictions = $this->getPredictionsFromSession();
        
        // Générer les différents types d'alertes
        $this->generateStockMinAlerts($products);
        $this->generateOverstockAlerts($products);
        $this->generateExpiryAlerts($products);
        $this->generatePredictionRiskAlerts($predictions);
        $this->generateMovementAnomalyAlerts($movements);
        
        return $this->alerts;
    }

    private function getProductsFromSession()
    {
        // Simuler les produits depuis la session ou données factices
        return session()->get('products', [
            (object)['id' => 1, 'name' => 'Laptop Pro 15"', 'current_stock' => 8, 'stock_min' => 10, 'expires_at' => now()->addDays(15)],
            (object)['id' => 2, 'name' => 'Moniteur 27"', 'current_stock' => 150, 'stock_min' => 20, 'expires_at' => now()->addDays(90)],
            (object)['id' => 3, 'name' => 'Clavier mécanique', 'current_stock' => 5, 'stock_min' => 15, 'expires_at' => now()->addDays(5)],
            (object)['id' => 4, 'name' => 'Souris sans fil', 'current_stock' => 120, 'stock_min' => 25, 'expires_at' => now()->addDays(60)],
        ]);
    }

    private function getMovementsFromSession()
    {
        return session()->get('movements', []);
    }

    private function getPredictionsFromSession()
    {
        return session()->get('predictions', []);
    }

    private function generateStockMinAlerts($products)
    {
        foreach ($products as $product) {
            if ($product->current_stock <= $product->stock_min) {
                $this->alerts[] = (object)[
                    'id' => 'stock_min_' . $product->id,
                    'type' => 'stock_min',
                    'level' => $product->current_stock <= ($product->stock_min / 2) ? 'critical' : 'warning',
                    'title' => 'Stock minimum atteint',
                    'message' => "Le produit '{$product->name}' a atteint son stock minimum ({$product->current_stock}/{$product->stock_min})",
                    'product_id' => $product->id,
                    'product' => $product,
                    'data' => [
                        'current_stock' => $product->current_stock,
                        'min_stock' => $product->stock_min,
                        'shortage_percentage' => round((1 - $product->current_stock / $product->stock_min) * 100, 1),
                    ],
                    'created_at' => now(),
                    'is_read' => false,
                    'email_sent' => false,
                ];
            }
        }
    }

    private function generateOverstockAlerts($products)
    {
        foreach ($products as $product) {
            if ($product->current_stock >= $this->config['overstock_threshold']) {
                $this->alerts[] = (object)[
                    'id' => 'overstock_' . $product->id,
                    'type' => 'overstock',
                    'level' => 'warning',
                    'title' => 'Produit en surstock',
                    'message' => "Le produit '{$product->name}' est en surstock ({$product->current_stock} unités)",
                    'product_id' => $product->id,
                    'product' => $product,
                    'data' => [
                        'current_stock' => $product->current_stock,
                        'overstock_amount' => $product->current_stock - $this->config['overstock_threshold'],
                        'suggested_action' => 'Promotion ou vente groupée recommandée',
                    ],
                    'created_at' => now(),
                    'is_read' => false,
                    'email_sent' => false,
                ];
            }
        }
    }

    private function generateExpiryAlerts($products)
    {
        foreach ($products as $product) {
            if (isset($product->expires_at)) {
                $daysToExpiry = $product->expires_at->diffInDays(now());
                
                if ($daysToExpiry <= $this->config['expiry_days_threshold']) {
                    $level = $daysToExpiry <= 7 ? 'critical' : 'warning';
                    
                    $this->alerts[] = (object)[
                        'id' => 'expiry_' . $product->id,
                        'type' => 'expiry',
                        'level' => $level,
                        'title' => 'Expiration proche',
                        'message' => "Le produit '{$product->name}' expire dans {$daysToExpiry} jours",
                        'product_id' => $product->id,
                        'product' => $product,
                        'data' => [
                            'expires_at' => $product->expires_at->format('Y-m-d'),
                            'days_to_expiry' => $daysToExpiry,
                            'urgency' => $level === 'critical' ? 'Urgent' : 'Modéré',
                        ],
                        'created_at' => now(),
                        'is_read' => false,
                        'email_sent' => false,
                    ];
                }
            }
        }
    }

    private function generatePredictionRiskAlerts($predictions)
    {
        foreach ($predictions as $prediction) {
            if (isset($prediction->risk_level) && $prediction->risk_level === 'critical') {
                $this->alerts[] = (object)[
                    'id' => 'prediction_risk_' . uniqid(),
                    'type' => 'prediction_risk',
                    'level' => 'critical',
                    'title' => 'Risque de rupture selon prédiction',
                    'message' => "Risque de rupture de stock détecté par l'IA pour les prochains jours",
                    'product_id' => $prediction->product_id ?? null,
                    'product' => $prediction->product ?? null,
                    'data' => [
                        'prediction_confidence' => $prediction->confidence ?? 0,
                        'risk_days' => $this->config['prediction_risk_days'],
                        'recommended_action' => 'Recommander réapprovisionnement immédiat',
                    ],
                    'created_at' => now(),
                    'is_read' => false,
                    'email_sent' => false,
                ];
            }
        }
    }

    private function generateMovementAnomalyAlerts($movements)
    {
        // Détecter les anomalies dans les mouvements de stock
        $recentMovements = collect($movements)->filter(function($movement) {
            return $movement->created_at && $movement->created_at->greaterThan(now()->subDays(7));
        });

        if ($recentMovements->count() > 50) {
            $this->alerts[] = (object)[
                'id' => 'movement_anomaly_' . uniqid(),
                'type' => 'movement_anomaly',
                'level' => 'warning',
                'title' => 'Activité anormale détectée',
                'message' => "Plus de 50 mouvements de stock enregistrés cette semaine",
                'product_id' => null,
                'product' => null,
                'data' => [
                    'movement_count' => $recentMovements->count(),
                    'period' => '7 derniers jours',
                    'analysis' => 'Activité inhabituelle qui mérite une attention',
                ],
                'created_at' => now(),
                'is_read' => false,
                'email_sent' => false,
            ];
        }
    }

    public function sendEmailAlerts($alerts)
    {
        $sentCount = 0;
        
        foreach ($alerts as $alert) {
            if (!$alert->email_sent) {
                // Simuler l'envoi d'email
                $this->simulateEmailSending($alert);
                $alert->email_sent = true;
                $alert->email_sent_at = now();
                $sentCount++;
                
                // Logger l'envoi
                \Log::info('Alert email sent', [
                    'alert_id' => $alert->id,
                    'type' => $alert->type,
                    'level' => $alert->level,
                    'sent_at' => now()->format('Y-m-d H:i:s')
                ]);
            }
        }
        
        return $sentCount;
    }

    private function simulateEmailSending($alert)
    {
        // Simulation d'envoi d'email
        $emailData = [
            'to' => 'admin@stockapp.com',
            'subject' => "[{$alert->level}] {$alert->title}",
            'content' => $alert->message,
            'alert_data' => $alert->data ?? [],
            'sent_at' => now()->format('Y-m-d H:i:s'),
        ];
        
        // Dans un vrai système, on utiliserait:
        // Mail::to($emailData['to'])->send(new AlertEmail($alert));
        
        return $emailData;
    }

    public function getDashboardSummary()
    {
        $alerts = $this->generateAllAlerts();
        
        return [
            'total' => count($alerts),
            'critical' => collect($alerts)->where('level', 'critical')->count(),
            'warning' => collect($alerts)->where('level', 'warning')->count(),
            'unread' => collect($alerts)->where('is_read', false)->count(),
            'by_type' => [
                'stock_min' => collect($alerts)->where('type', 'stock_min')->count(),
                'overstock' => collect($alerts)->where('type', 'overstock')->count(),
                'expiry' => collect($alerts)->where('type', 'expiry')->count(),
                'prediction_risk' => collect($alerts)->where('type', 'prediction_risk')->count(),
                'movement_anomaly' => collect($alerts)->where('type', 'movement_anomaly')->count(),
            ],
            'recent' => collect($alerts)->sortByDesc('created_at')->take(5),
        ];
    }
}
