<?php

return [
    // Configuration optimisée pour Render
    'performance' => [
        'cache_driver' => env('CACHE_DRIVER', 'file'),
        'session_driver' => env('SESSION_DRIVER', 'file'),
        'queue_connection' => env('QUEUE_CONNECTION', 'sync'),
    ],
    
    'optimizations' => [
        'opcache_enable' => true,
        'opcache_memory_consumption' => 128,
        'max_execution_time' => 30,
    ],
    
    'storage' => [
        'use_temp_uploads' => env('RENDER', false),
        'temp_disk' => 'local',
    ],
];
