<?php

require_once '../core/helpers/JwtHelper.php';

class LoginMiddleware {
    public static function handle() {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized - No token provided']);
            exit;
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);

        $decoded = JwtHelper::validateToken($token);
        if (!$decoded) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized - Invalid or expired token']);
            exit;
        }

        $GLOBALS['current_user'] = [
            'username' => $decoded['username'],
            'role' => $decoded['role']
        ];
    }
}
