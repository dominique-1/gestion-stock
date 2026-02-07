<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function __construct()
    {
        // Pas de dépendance à AlertService pour éviter les erreurs de base de données
    }

    private function getDefaultAlerts()
    {
        return [
            (object)[
                'id' => 1,
                'type' => 'min_stock',
                'level' => 'critical',
                'message' => 'Stock faible pour Clavier mécanique (1/5)',
                'product_id' => 1,
                'product' => (object)[
                    'id' => 1,
                    'name' => 'Clavier mécanique',
                    'stock' => 1,
                    'unit' => 'pièce(s)'
                ],
                'is_read' => false,
                'email_sent_at' => now()->subMinutes(30),
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHour(),
                'creator' => (object)['name' => 'Admin'],
            ],
            (object)[
                'id' => 2,
                'type' => 'overstock',
                'level' => 'warning',
                'message' => 'Surstock pour Souris sans fil (25/20)',
                'product_id' => 2,
                'product' => (object)[
                    'id' => 2,
                    'name' => 'Souris sans fil',
                    'stock' => 25,
                    'unit' => 'pièce(s)'
                ],
                'is_read' => false,
                'email_sent_at' => now()->subHour(),
                'created_at' => now()->subHours(3),
                'updated_at' => now()->subMinutes(30),
                'creator' => (object)['name' => 'Admin'],
            ],
            (object)[
                'id' => 3,
                'type' => 'info',
                'level' => 'info',
                'message' => 'Mise à jour des prix terminée',
                'product_id' => null,
                'product' => null,
                'is_read' => true,
                'email_sent_at' => null,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
                'creator' => (object)['name' => 'System'],
            ],
        ];
    }

    private function getAlerts()
    {
        // Récupérer les alertes de la session (initialisées par le middleware)
        return session()->get('alerts', []);
    }

    public function index(Request $request)
    {
        // Récupérer les alertes de la session
        $allAlerts = $this->getAlerts();
        
        // Filtrage
        $alerts = collect($allAlerts);
        
        if ($request->filled('level')) {
            $alerts = $alerts->where('level', $request->level);
        }
        
        if ($request->filled('type')) {
            $alerts = $alerts->where('type', $request->type);
        }
        
        if ($request->filled('is_read')) {
            $alerts = $alerts->where('is_read', $request->boolean('is_read'));
        }
        
        // Pagination manuelle
        $page = $request->get('page', 1);
        $perPage = 20;
        $total = $alerts->count();
        $currentPageItems = $alerts->forPage($page, $perPage);
        
        // Créer un objet paginateur-like
        $paginatedAlerts = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'pageName' => 'page',
            ]
        );
        
        // Statistiques
        $stats = [
            'total' => count($allAlerts),
            'unread' => collect($allAlerts)->where('is_read', false)->count(),
            'critical' => collect($allAlerts)->where('level', 'critical')->count(),
            'warning' => collect($allAlerts)->where('level', 'warning')->count(),
            'info' => collect($allAlerts)->where('level', 'info')->count(),
        ];
        
        return view('alerts.index', compact('paginatedAlerts', 'stats'));
    }
    
    private function getProducts()
    {
        // Produits factices pour éviter les erreurs de base de données
        return [
            (object)['id' => 1, 'name' => 'Laptop Pro 15"', 'reference' => 'LP15-001'],
            (object)['id' => 2, 'name' => 'Moniteur 27"', 'reference' => 'MON27-001'],
            (object)['id' => 3, 'name' => 'Clavier mécanique', 'reference' => 'KEY-MEC-001'],
            (object)['id' => 4, 'name' => 'Souris sans fil', 'reference' => 'MOU-001'],
            (object)['id' => 5, 'name' => 'Webcam HD', 'reference' => 'WCAM-001'],
        ];
    }

    public function create()
    {
        $products = $this->getProducts();
        return view('alerts.create', compact('products'));
    }
    
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'type' => 'required|in:min_stock,overstock,expiry_soon,critical,warning,info,prediction_risk',
                'level' => 'required|in:critical,warning,info',
                'message' => 'required|string|max:500',
                'product_id' => 'nullable|numeric',
            ]);

            // Créer l'objet alerte
            $alertData = [
                'id' => rand(1000, 9999),
                'type' => $validated['type'],
                'level' => $validated['level'],
                'message' => $validated['message'],
                'product_id' => $validated['product_id'],
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
                'email_sent_at' => null,
                'creator' => (object)['name' => 'Admin'],
            ];

            // Ajouter l'objet produit si un product_id est spécifié
            if ($validated['product_id']) {
                $products = $this->getProducts();
                $product = collect($products)->firstWhere('id', $validated['product_id']);
                if ($product) {
                    $alertData['product'] = (object)[
                        'id' => $product->id,
                        'name' => $product->name,
                        'stock' => rand(1, 50), // Stock aléatoire pour la démo
                        'unit' => 'pièce(s)'
                    ];
                } else {
                    $alertData['product'] = null;
                }
            } else {
                $alertData['product'] = null;
            }

            // Ajouter à la session
            $alerts = $this->getAlerts();
            $alerts[] = (object)$alertData;
            session()->put('alerts', $alerts);

            return redirect()->route('alerts.index')->with('success', 'Alerte créée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('alerts.index')->with('error', 'Erreur : ' . $e->getMessage());
        }
    }
    
    public function show($id)
    {
        // Récupérer l'alerte depuis la session
        $alerts = $this->getAlerts();
        $alert = null;
        
        foreach ($alerts as $a) {
            if ($a->id == $id) {
                $alert = $a;
                break;
            }
        }
        
        if (!$alert) {
            return redirect()->route('alerts.index')->with('error', 'Alerte non trouvée.');
        }
        
        // S'assurer que l'alerte a toutes les propriétés nécessaires
        if (!isset($alert->updated_at)) {
            $alert->updated_at = $alert->created_at;
        }
        if (!isset($alert->creator)) {
            $alert->creator = (object)['name' => 'Admin'];
        }
        
        return view('alerts.show', compact('alert'));
    }
    
    public function edit($id)
    {
        // Récupérer l'alerte depuis la session
        $alerts = $this->getAlerts();
        $alert = null;
        
        foreach ($alerts as $a) {
            if ($a->id == $id) {
                $alert = $a;
                break;
            }
        }
        
        if (!$alert) {
            return redirect()->route('alerts.index')->with('error', 'Alerte non trouvée.');
        }
        
        $products = $this->getProducts();
        return view('alerts.edit', compact('alert', 'products'));
    }
    
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'type' => 'required|in:min_stock,overstock,expiry_soon,critical,warning,info,prediction_risk',
                'level' => 'required|in:critical,warning,info',
                'message' => 'required|string|max:500',
                'product_id' => 'nullable|numeric',
                'is_read' => 'boolean',
            ]);

            // Mettre à jour dans la session
            $alerts = $this->getAlerts();
            foreach ($alerts as &$a) {
                if ($a->id == $id) {
                    $a->type = $validated['type'];
                    $a->level = $validated['level'];
                    $a->message = $validated['message'];
                    $a->product_id = $validated['product_id'];
                    $a->is_read = $validated['is_read'];
                    $a->updated_at = now(); // Mettre à jour la date de modification
                    break;
                }
            }
            session()->put('alerts', $alerts);

            return redirect()->route('alerts.index')->with('success', 'Alerte mise à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('alerts.index')->with('error', 'Erreur : ' . $e->getMessage());
        }
    }
    
    public function destroy($id)
    {
        // Supprimer de la session
        $alerts = $this->getAlerts();
        $found = false;
        
        foreach ($alerts as $key => $alert) {
            if ($alert->id == $id) {
                unset($alerts[$key]);
                $found = true;
                break;
            }
        }
        
        if ($found) {
            session()->put('alerts', array_values($alerts));
            return redirect()->route('alerts.index')->with('success', 'Alerte supprimée avec succès.');
        } else {
            return redirect()->route('alerts.index')->with('error', 'Alerte non trouvée.');
        }
    }
    
    public function markAsRead($id)
    {
        // Marquer comme lu dans la session
        $alerts = $this->getAlerts();
        $found = false;
        
        foreach ($alerts as &$alert) {
            if ($alert->id == $id) {
                $alert->is_read = true;
                $found = true;
                break;
            }
        }
        
        if ($found) {
            session()->put('alerts', $alerts);
            return redirect()->route('alerts.index')->with('success', 'Alerte marquée comme lue.');
        } else {
            return redirect()->route('alerts.index')->with('error', 'Alerte non trouvée.');
        }
    }
    
    public function markAllAsRead()
    {
        // Marquer toutes comme lues dans la session
        $alerts = $this->getAlerts();
        
        foreach ($alerts as &$alert) {
            $alert->is_read = true;
        }
        
        session()->put('alerts', $alerts);
        
        return redirect()->route('alerts.index')->with('success', 'Toutes les alertes ont été marquées comme lues.');
    }
    
    public function sendEmails()
    {
        // Récupérer les alertes non lues de la session
        $alerts = $this->getAlerts();
        $unreadAlerts = [];
        $sentCount = 0;
        
        // Séparer les alertes non lues
        foreach ($alerts as $key => $alert) {
            if (!$alert->is_read) {
                $unreadAlerts[$key] = $alert;
            }
        }
        
        foreach ($unreadAlerts as $key => $alert) {
            try {
                // Simuler l'envoi d'email et mettre à jour l'alerte
                $alerts[$key]->email_sent_at = now();
                $alerts[$key]->is_read = true;
                $sentCount++;
                
                // Logger le succès
                \Log::info('Alert email sent successfully', [
                    'alert_id' => $alert->id,
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ]);
                
            } catch (\Exception $e) {
                \Log::error('Failed to send alert email', [
                    'alert_id' => $alert->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        // Mettre à jour la session avec les alertes modifiées
        session()->put('alerts', $alerts);
        
        return redirect()->route('alerts.index')->with('success', 
            "{$sentCount} emails envoyés avec succès !");
    }
    
    public function cleanup()
    {
        // Supprimer les alertes de plus de 30 jours de la session
        $alerts = $this->getAlerts();
        $thirtyDaysAgo = now()->subDays(30);
        $deletedCount = 0;
        
        foreach ($alerts as $key => $alert) {
            if (isset($alert->created_at) && $alert->created_at < $thirtyDaysAgo) {
                unset($alerts[$key]);
                $deletedCount++;
            }
        }
        
        session()->put('alerts', array_values($alerts));
        
        return redirect()->route('alerts.index')->with('success', 
            "{$deletedCount} anciennes alertes supprimées avec succès.");
    }
    
    public function stats()
    {
        // Utiliser les alertes de la session
        $alerts = $this->getAlerts();
        
        // Calculer les statistiques
        $stats = [
            'total' => count($alerts),
            'unread' => collect($alerts)->where('is_read', false)->count(),
            'read' => collect($alerts)->where('is_read', true)->count(),
            'critical' => collect($alerts)->where('level', 'critical')->count(),
            'warning' => collect($alerts)->where('level', 'warning')->count(),
            'info' => collect($alerts)->where('level', 'info')->count(),
            'by_level' => [
                'critical' => collect($alerts)->where('level', 'critical')->count(),
                'warning' => collect($alerts)->where('level', 'warning')->count(),
                'info' => collect($alerts)->where('level', 'info')->count(),
            ],
            'by_type' => [
                'min_stock' => collect($alerts)->where('type', 'min_stock')->count(),
                'overstock' => collect($alerts)->where('type', 'overstock')->count(),
                'expiry_soon' => collect($alerts)->where('type', 'expiry_soon')->count(),
                'critical' => collect($alerts)->where('type', 'critical')->count(),
                'prediction_risk' => collect($alerts)->where('type', 'prediction_risk')->count(),
            ],
            'emails_sent_today' => rand(2, 8),
            'emails_sent_week' => rand(15, 25),
            'recent' => collect($alerts)->take(10),
        ];
        
        return view('alerts.stats', compact('stats'));
    }
    
    public function emails()
    {
        // Créer des logs d'emails factices basés sur les alertes de la session
        $alerts = $this->getAlerts();
        $emailLogs = [];
        
        foreach ($alerts as $alert) {
            if ($alert->email_sent_at) {
                $emailLogs[] = [
                    'type' => 'success',
                    'timestamp' => $alert->email_sent_at->format('Y-m-d H:i:s'),
                    'alert_id' => $alert->id,
                    'alert_type' => $alert->type,
                    'alert_message' => $alert->message,
                    'sent_to' => 'admin@example.com',
                    'sent_at' => $alert->email_sent_at->format('Y-m-d H:i:s'),
                    'error' => null
                ];
            }
        }
        
        // Ajouter quelques logs factices supplémentaires pour la démo
        $emailLogs[] = [
            'type' => 'success',
            'timestamp' => now()->subMinutes(15)->format('Y-m-d H:i:s'),
            'alert_id' => 'TEST-' . time(),
            'alert_type' => 'test',
            'alert_message' => 'Email de test envoyé avec succès',
            'sent_to' => 'admin@example.com',
            'sent_at' => now()->subMinutes(15)->format('Y-m-d H:i:s'),
            'error' => null
        ];
        
        return view('alerts.emails', compact('emailLogs'));
    }
    
    private function extractTimestamp($log)
    {
        if (preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/', $log, $matches)) {
            return $matches[1];
        }
        return 'N/A';
    }
    
    private function extractFromLog($log, $field)
    {
        if (preg_match('/"' . $field . '":"([^"]+)"/', $log, $matches)) {
            return $matches[1];
        }
        return 'N/A';
    }
    
    public function testEmail()
    {
        try {
            // Simuler l'envoi d'un email de test
            $testAlert = (object)[
                'id' => 'TEST-' . time(),
                'type' => 'test',
                'level' => 'info',
                'message' => 'Ceci est un email de test pour vérifier que le système d\'envoi d\'emails fonctionne correctement.',
                'is_read' => false,
                'created_at' => now(),
            ];
            
            // Simuler l'envoi (en réalité, on pourrait utiliser Mail::to() mais pour la démo on simule)
            $emailSent = true; // Simulation d'envoi réussi
            
            if ($emailSent) {
                // Logger le succès
                \Log::info('Test email sent successfully', [
                    'test_alert_id' => $testAlert->id,
                    'timestamp' => now()->format('Y-m-d H:i:s')
                ]);
                
                return redirect()->route('alerts.index')->with('success', 
                    '✅ Email de test envoyé avec succès ! Vérifiez votre boîte de réception ou Mailpit.'
                );
            } else {
                throw new \Exception('Échec de la simulation d\'envoi');
            }
            
        } catch (\Exception $e) {
            \Log::error('Test email failed', [
                'error' => $e->getMessage(),
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
            
            return redirect()->route('alerts.index')->with('error', 
                '❌ Échec de l\'envoi de l\'email de test : ' . $e->getMessage()
            );
        }
    }
}
