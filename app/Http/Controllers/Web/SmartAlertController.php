<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\DatabaseSmartAlertService;
use App\Models\SmartAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SmartAlertController extends Controller
{
    private $alertService;

    public function __construct(DatabaseSmartAlertService $alertService)
    {
        $this->alertService = $alertService;
    }

    public function index(Request $request)
    {
        // Générer toutes les alertes intelligentes
        $this->alertService->generateAllAlerts();
        
        // Récupérer les alertes avec relations
        $query = SmartAlert::with('product');
        
        // Filtrage
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }
        
        if ($request->filled('is_read')) {
            $isRead = $request->boolean('is_read');
            $query->where('is_read', $isRead);
        }
        
        // Pagination
        $alerts = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Statistiques
        $stats = $this->alertService->getDashboardSummary();
        
        return view('smart-alerts.index', compact('alerts', 'stats'));
    }

    public function dashboard()
    {
        $summary = $this->alertService->getDashboardSummary();
        
        return response()->json([
            'success' => true,
            'data' => $summary,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
    }

    public function sendEmails()
    {
        $unreadAlerts = SmartAlert::unread()->get();
        $sentCount = $this->alertService->sendEmailAlerts($unreadAlerts);
        
        return redirect()->route('smart-alerts.index')->with('success', 
            "{$sentCount} alertes envoyées par email avec succès !");
    }

    public function markAsRead($id)
    {
        $alert = SmartAlert::findOrFail($id);
        $alert->markAsRead();
        
        return response()->json(['success' => true, 'message' => 'Alerte marquée comme lue']);
    }

    public function markAllAsRead()
    {
        $count = SmartAlert::unread()->count();
        
        SmartAlert::unread()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
        
        return redirect()->route('smart-alerts.index')->with('success', 
            "{$count} alertes marquées comme lues.");
    }

    public function dismiss($id)
    {
        $alert = SmartAlert::findOrFail($id);
        $alert->dismiss();
        
        return response()->json(['success' => true, 'message' => 'Alerte ignorée']);
    }

    public function analytics()
    {
        $alerts = SmartAlert::with('product')->get();
        
        $analytics = [
            'trends' => $this->calculateAlertTrends($alerts),
            'distribution' => $this->calculateAlertDistribution($alerts),
            'performance' => $this->alertService->getDashboardSummary()['performance'],
            'predictions' => $this->generatePredictions($alerts),
        ];
        
        return view('smart-alerts.analytics', compact('analytics'));
    }

    private function calculateAlertTrends($alerts)
    {
        // Calculer les tendances sur les 7 derniers jours
        $trends = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayAlerts = $alerts->filter(function($alert) use ($date) {
                return $alert->created_at->format('Y-m-d') === $date->format('Y-m-d');
            });
            
            $trends[] = [
                'date' => $date->format('d/m'),
                'total' => $dayAlerts->count(),
                'critical' => $dayAlerts->where('level', 'critical')->count(),
                'warning' => $dayAlerts->where('level', 'warning')->count(),
            ];
        }
        
        return $trends;
    }

    private function calculateAlertDistribution($alerts)
    {
        $distribution = [];
        
        foreach ($alerts as $alert) {
            $type = $alert->type;
            if (!isset($distribution[$type])) {
                $distribution[$type] = [
                    'name' => $this->getAlertTypeName($type),
                    'count' => 0,
                    'critical' => 0,
                    'warning' => 0,
                ];
            }
            
            $distribution[$type]['count']++;
            $distribution[$type][$alert->level]++;
        }
        
        return array_values($distribution);
    }

    private function generatePredictions($alerts)
    {
        // Prédire les alertes futures basées sur les tendances actuelles
        return [
            'next_24h' => $this->predictAlerts($alerts, 1),
            'next_week' => $this->predictAlerts($alerts, 7),
            'critical_probability' => $this->calculateCriticalProbability($alerts),
            'recommendations' => [
                'Optimiser les niveaux de stock minimum',
                'Mettre en place des alertes prédictives',
                'Automatiser les réapprovisionnements',
                'Surveiller les tendances saisonnières',
            ],
        ];
    }

    private function predictAlerts($alerts, $days)
    {
        // Basé sur la moyenne des 7 derniers jours
        $recentAlerts = $alerts->where('created_at', '>=', now()->subDays(7))->count();
        $dailyAverage = $recentAlerts / 7;
        
        return max(1, round($dailyAverage * $days * 1.2)); // 20% d'augmentation pour la prédiction
    }

    private function calculateCriticalProbability($alerts)
    {
        $totalAlerts = $alerts->count();
        $criticalAlerts = $alerts->where('level', 'critical')->count();
        
        if ($totalAlerts === 0) {
            return 0;
        }
        
        return round(($criticalAlerts / $totalAlerts) * 100);
    }

    private function getAlertTypeName($type)
    {
        $names = [
            'stock_min' => 'Stock minimum',
            'overstock' => 'Surstock',
            'expiry' => 'Expiration',
            'prediction_risk' => 'Risque prédit',
            'movement_anomaly' => 'Anomalie de mouvement',
        ];
        
        return $names[$type] ?? $type;
    }

    // API methods
    public function apiAlerts(Request $request)
    {
        $alerts = SmartAlert::with('product')->orderBy('created_at', 'desc');
        
        // Apply filters
        if ($request->filled('type')) {
            $alerts->where('type', $request->type);
        }
        
        if ($request->filled('level')) {
            $alerts->where('level', $request->level);
        }
        
        if ($request->filled('is_read')) {
            $alerts->where('is_read', $request->boolean('is_read'));
        }
        
        if ($request->filled('limit')) {
            $alerts->limit($request->get('limit'));
        }
        
        return response()->json([
            'success' => true,
            'data' => $alerts->get(),
            'total' => $alerts->count(),
        ]);
    }

    public function apiStats()
    {
        $stats = $this->alertService->getDashboardSummary();
        
        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    public function cleanup()
    {
        $deletedCount = SmartAlert::cleanupOldAlerts(30);
        
        return response()->json([
            'success' => true,
            'message' => "{$deletedCount} anciennes alertes supprimées",
        ]);
    }
}
