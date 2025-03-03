<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHelper {
    private static $secretKey = 'my_super_secret_key';
    private static $refreshTokensFile = '../storage/refresh_tokens.json';

    public static function generateToken($username, $role) {
        $payload = [
            'iss' => "localhost",
            'iat' => time(),
            'exp' => time() + 3600,
            'username' => $username,
            'role' => $role
        ];

        return JWT::encode($payload, self::$secretKey, 'HS256');
    }

    public static function generateRefreshToken($username) {
        $refreshToken = bin2hex(random_bytes(32));
        $tokens = self::loadRefreshTokens();
        $tokens[$refreshToken] = [
            'username' => $username,
            'created_at' => time()
        ];
        file_put_contents(self::$refreshTokensFile, json_encode($tokens, JSON_PRETTY_PRINT));
        return $refreshToken;
    }

    public static function validateToken($token) {
        try {
            $decoded = JWT::decode($token, new Key(self::$secretKey, 'HS256'));
            return (array) $decoded;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function validateRefreshToken($refreshToken) {
        $tokens = self::loadRefreshTokens();
        if (isset($tokens[$refreshToken])) {
            return $tokens[$refreshToken]['username'];
        }
        return null;
    }

    private static function loadRefreshTokens() {
        if (!file_exists(self::$refreshTokensFile)) {
            return [];
        }
        return json_decode(file_get_contents(self::$refreshTokensFile), true);
    }

    public static function revokeRefreshToken($refreshToken) {
        $tokens = self::loadRefreshTokens();
        if (isset($tokens[$refreshToken])) {
            unset($tokens[$refreshToken]);
            file_put_contents(self::$refreshTokensFile, json_encode($tokens, JSON_PRETTY_PRINT));
        }
    }
}
