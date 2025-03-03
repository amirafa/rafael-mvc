<?php

class RateLimitMiddleware {
    private static $rateLimit = 5; 
    private static $storageFile = '../core/middleware/rate_limit_storage.json';

    public static function handle() {
        $ip = $_SERVER['REMOTE_ADDR'];

        $data = file_exists(self::$storageFile) ? json_decode(file_get_contents(self::$storageFile), true) : [];
        $currentTime = time();

        foreach ($data as $ipAddress => $info) {
            if ($currentTime - $info['timestamp'] > 60) unset($data[$ipAddress]);
        }

        if (!isset($data[$ip])) $data[$ip] = ['count' => 0, 'timestamp' => $currentTime];

        if ($data[$ip]['count'] >= self::$rateLimit) {
            http_response_code(429);
            echo json_encode(['message' => 'Too many requests - slow down']);
            exit;
        }

        $data[$ip]['count']++;
        $data[$ip]['timestamp'] = $currentTime;
        file_put_contents(self::$storageFile, json_encode($data));
    }
}
