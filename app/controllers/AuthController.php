<?php

require_once '../core/helpers/JwtHelper.php';

class AuthController extends Controller {

    public function refreshToken() {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['refresh_token'])) {
            $this->jsonResponse(['message' => 'Refresh token is required'], 400);
        }

        $username = JwtHelper::validateRefreshToken($input['refresh_token']);
        if (!$username) {
            $this->jsonResponse(['message' => 'Invalid or expired refresh token'], 401);
        }

        // Generate new access token and refresh token
        $newAccessToken = JwtHelper::generateToken($username);
        $newRefreshToken = JwtHelper::generateRefreshToken($username);

        // Revoke old refresh token
        JwtHelper::revokeRefreshToken($input['refresh_token']);

        $this->jsonResponse([
            'access_token' => $newAccessToken,
            'refresh_token' => $newRefreshToken,
            'message' => 'Token refreshed successfully'
        ]);
    }
}
