<?php

require_once '../core/helpers/JwtHelper.php';

class LoginController extends Controller {

    private $usersFile = '../storage/users.json';

    public function login() {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['username']) || empty($input['password'])) {
            $this->jsonResponse(['message' => 'Username and password are required'], 400);
        }

        $users = $this->loadUsers();

        if (!isset($users[$input['username']]) || !password_verify($input['password'], $users[$input['username']]['password'])) {
            $this->jsonResponse(['message' => 'Invalid credentials'], 401);
        }

        $role = $users[$input['username']]['role'];

        $token = JwtHelper::generateToken($input['username'], $role);
        $refreshToken = JwtHelper::generateRefreshToken($input['username']);

        $this->jsonResponse([
            'message' => 'Login successful',
            'access_token' => $token,
            'refresh_token' => $refreshToken
        ]);
    }

    private function loadUsers() {
        if (!file_exists($this->usersFile)) {
            return [];
        }
        return json_decode(file_get_contents($this->usersFile), true);
    }
}
