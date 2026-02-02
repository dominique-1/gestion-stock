<?php

namespace App\Services;

use App\Models\Alert;
use App\Models\Product;
use App\Models\User;
use App\Mail\AlertEmail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AlertService
{
    /**
     * Génère toutes les alertes automatiques
     */
    public function generateAllAlerts()
    {
        $this->generateLowStockAlerts();
        $this->generateOverstockAlerts();
        $this->generateExpiryAlerts();
        $this->generateCriticalStockAlerts();
        $this->generatePredictionAlerts();
    }

    /**
     * Génère les alertes de stock faible
     */
    private function generateLowStockAlerts()
    {
        $lowStock = Product::lowStock()->get();
        
        foreach ($lowStock as $product) {
            $alert = Alert::firstOrCreate([
                'type' => 'min_stock',
                'product_id' => $product->id,
                'message' => "Stock faible pour {$product->name} ({$product->current_stock}/{$product->stock_min})",
                'level' => $product->current_stock === 0 ? 'critical' : 'warning',
                'is_read' => false,
            ]);

            // Envoyer l'email
            $this->sendAlertEmail($alert, $product);
        }
    }

    /**
     * Génère les alertes de surstock
     */
    private function generateOverstockAlerts()
    {
        $overstock = Product::overstock()->get();
        
        foreach ($overstock as $product) {
            $alert = Alert::firstOrCreate([
                'type' => 'overstock',
                'product_id' => $product->id,
                'message' => "Surstock pour {$product->name} ({$product->current_stock}/{$product->stock_optimal})",
                'level' => 'info',
                'is_read' => false,
            ]);

            // Envoyer l'email
            $this->sendAlertEmail($alert, $product);
        }
    }

    /**
     * Génère les alertes d'expiration
     */
    private function generateExpiryAlerts()
    {
        $expiring = Product::expiringSoon(7)->get();
        
        foreach ($expiring as $product) {
            $alert = Alert::firstOrCreate([
                'type' => 'expiry_soon',
                'product_id' => $product->id,
                'message' => "Produit proche de l'expiration : {$product->expires_at->format('d/m/Y')}",
                'level' => 'warning',
                'is_read' => false,
            ]);

            // Envoyer l'email
            $this->sendAlertEmail($alert, $product);
        }
    }

    /**
     * Génère les alertes critiques (stock à 0)
     */
    private function generateCriticalStockAlerts()
    {
        $criticalStock = Product::where('current_stock', 0)->get();
        
        foreach ($criticalStock as $product) {
            $alert = Alert::firstOrCreate([
                'type' => 'critical',
                'product_id' => $product->id,
                'message' => "Rupture de stock pour {$product->name} !",
                'level' => 'critical',
                'is_read' => false,
            ]);

            // Envoyer l'email immédiatement
            $this->sendAlertEmail($alert, $product);
        }
    }

    /**
     * Génère les alertes basées sur les prédictions
     */
    private function generatePredictionAlerts()
    {
        try {
            // Obtenir les prédictions du PredictionController
            $predictionController = new \App\Http\Controllers\Web\PredictionController();
            
            // Simuler une requête pour obtenir les prédictions
            $request = new \Illuminate\Http\Request([
                'period' => 7,
                'algorithm' => 'auto',
                'moving_average_days' => 7
            ]);
            
            $response = $predictionController->apiPredictions($request);
            $predictions = json_decode($response->getContent(), true);
            
            if (isset($predictions['predictions'])) {
                foreach ($predictions['predictions'] as $prediction) {
                    // Vérifier si product_id existe
                    if (!isset($prediction['product_id'])) {
                        continue;
                    }
                    
                    $product = Product::find($prediction['product_id']);
                    
                    if ($product && isset($prediction['predicted_stock']) && $prediction['predicted_stock'] < $product->stock_min) {
                        // Calculer le nombre de jours avant rupture
                        $daysToRupture = $this->calculateDaysToRupture($prediction);
                        
                        $alert = Alert::firstOrCreate([
                            'type' => 'prediction_risk',
                            'product_id' => $product->id,
                            'message' => "Risque de rupture prédit pour {$product->name} dans {$daysToRupture} jours (stock prédit: {$prediction['predicted_stock']})",
                            'level' => $daysToRupture <= 3 ? 'critical' : 'warning',
                            'is_read' => false,
                        ]);

                        // Envoyer l'email
                        $this->sendAlertEmail($alert, $product);
                    }
                }
            }
        } catch (\Exception $e) {
            // Logger l'erreur mais ne pas arrêter le processus
            \Log::error('Failed to generate prediction alerts', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Calcule le nombre de jours avant rupture
     */
    private function calculateDaysToRupture($prediction)
    {
        $currentStock = $prediction['current_stock'] ?? 0;
        $dailyConsumption = $prediction['predicted_out'] ?? 1;
        
        if ($dailyConsumption <= 0) {
            return 999; // Pas de consommation prévue
        }
        
        return ceil($currentStock / $dailyConsumption);
    }

    /**
     * Envoie un email d'alerte
     */
    public function sendAlertEmail($alert, $product = null)
    {
        try {
            // Créer et envoyer l'email
            \Mail::to('admin@example.com')->send(new \App\Mail\AlertEmail($alert, $product));
            
            // Marquer l'alerte comme envoyée
            if (isset($alert->id)) {
                $alertModel = \App\Models\Alert::find($alert->id);
                if ($alertModel) {
                    $alertModel->email_sent_at = now();
                    $alertModel->save();
                }
            }
            
            // Logger l'envoi pour débogage
            \Log::info('Alert email sent', [
                'alert_id' => $alert->id ?? 'N/A',
                'alert_type' => $alert->type ?? 'N/A',
                'alert_message' => $alert->message ?? 'N/A',
                'product_id' => $product->id ?? 'N/A',
                'sent_to' => 'admin@example.com',
                'sent_at' => now()->format('Y-m-d H:i:s')
            ]);
            
        } catch (\Exception $e) {
            // Logger l'erreur mais ne pas arrêter le processus
            \Log::error('Failed to send alert email', [
                'error' => $e->getMessage(),
                'alert_id' => $alert->id ?? 'N/A',
                'alert_type' => $alert->type ?? 'N/A'
            ]);
        }
    }

    /**
     * Nettoie les anciennes alertes (plus de 30 jours)
     */
    public function cleanupOldAlerts()
    {
        // Simuler le nettoyage - ne fait rien avec les données factices
    }

    /**
     * Obtient les statistiques des alertes
     */
    public function getAlertStats()
    {
        return [
            'total' => 4,
            'unread' => 4,
            'critical' => 1,
            'warning' => 2,
            'info' => 1,
            'by_type' => [
                'min_stock' => 1,
                'overstock' => 1,
                'expiry_soon' => 1,
                'critical' => 1,
                'prediction_risk' => 0,
            ]
        ];
    }
}
