<?php

class VerifyController extends Controller {

    private $usersFile = '../storage/users.json';

    public function verifyEmail() {
        $token = $_GET['token'] ?? null;

        if (!$token) {
            $this->jsonResponse(['message' => 'Verification token is required'], 400);
            return;
        }

        $users = $this->loadUsers();

        foreach ($users as $username => $userData) {
            if (isset($userData['verification_token']) && $userData['verification_token'] === $token) {
                
                if (empty($userData['email'])) {
                    $this->jsonResponse(['message' => 'Email address not found for this user'], 400);
                    return;
                }

                $users[$username]['verified'] = true;
                $users[$username]['verification_token'] = null;

                file_put_contents($this->usersFile, json_encode($users, JSON_PRETTY_PRINT));

                $this->jsonResponse(['message' => 'Email successfully verified']);
                return;
            }
        }

        $this->jsonResponse(['message' => 'Invalid or expired verification token'], 400);
    }

    private function loadUsers() {
        if (!file_exists($this->usersFile)) {
            return [];
        }
        return json_decode(file_get_contents($this->usersFile), true);
    }
}
