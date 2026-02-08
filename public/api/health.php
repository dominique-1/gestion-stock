<?php
header('Content-Type: application/json');
http_response_code(200);

echo json_encode([
    'status' => 'healthy',
    'timestamp' => now()->toISOString(),
    'version' => '1.0.0',
    'environment' => config('app.env'),
    'database' => \Illuminate\Support\Facades\DB::connection()->getPdo() ? 'connected' : 'disconnected'
]);
