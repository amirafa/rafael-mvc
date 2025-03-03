<?php

require_once '../core/helpers/JwtHelper.php';

class RegisterController extends Controller {

    private $usersFile = '../storage/users.json';

    public function register() {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['username']) || empty($input['password']) || empty($input['email'])) {
            $this->jsonResponse(['message' => 'Username, email, and password are required'], 400);
            return;
        }

        $users = $this->loadUsers();

        if (isset($users[$input['username']])) {
            $this->jsonResponse(['message' => 'Username already exists'], 400);
            return;
        }

        $role = $input['role'] ?? 'user';  // Default role is "user"
        $verificationToken = bin2hex(random_bytes(32));

        // Save user with hashed password, role and email verification token
        $users[$input['username']] = [
            'password' => password_hash($input['password'], PASSWORD_DEFAULT),
            'email' => $input['email'],
            'role' => $role,
            'verified' => false,
            'verification_token' => $verificationToken
        ];

        file_put_contents($this->usersFile, json_encode($users, JSON_PRETTY_PRINT));

        // In a real system, send an email with the verification link
        $verificationLink = "http://localhost/verify-email?token=$verificationToken";

        $this->jsonResponse([
            'message' => 'User registered successfully, please verify your email',
            'verification_link' => $verificationLink
        ]);
    }

    private function loadUsers() {
        if (!file_exists($this->usersFile)) {
            return [];
        }
        return json_decode(file_get_contents($this->usersFile), true);
    }
}
