<?php

class RoleMiddleware {
    public static function handle($requiredRole) {
        if (!isset($GLOBALS['current_user'])) {
            http_response_code(403);
            echo json_encode(['message' => 'Forbidden - No user information available']);
            exit;
        }

        if ($GLOBALS['current_user']['role'] !== $requiredRole) {
            http_response_code(403);
            echo json_encode(['message' => 'Forbidden - Insufficient permissions']);
            exit;
        }
    }
}
