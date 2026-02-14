<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('user')) {
            // #region agent log
            try {
                $logPath = base_path('.cursor/debug.log');
                $logDir = dirname($logPath);
                if (!is_dir($logDir)) {
                    @mkdir($logDir, 0755, true);
                }
                $entry = [
                    'id' => 'log_'.uniqid(),
                    'timestamp' => (int) round(microtime(true) * 1000),
                    'runId' => 'pre-fix',
                    'hypothesisId' => 'H4',
                    'location' => 'CheckUserSession',
                    'message' => 'User not in session, redirecting to login',
                    'data' => [
                        'uri' => $request->getRequestUri(),
                    ],
                ];
                file_put_contents($logPath, json_encode($entry).PHP_EOL, FILE_APPEND);
            } catch (\Throwable $e) {
                // ignore logging errors
            }
            // #endregion agent log

            return redirect()->route('login');
        }

        return $next($request);
    }
}
