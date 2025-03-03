<?php

require_once '../core/middleware/RoleMiddleware.php';

class AdminController extends Controller {

    public function dashboard() {
        RoleMiddleware::handle('admin');  // Enforce that only admin can access this route
        $this->jsonResponse(['message' => 'Welcome to the admin dashboard']);
    }
}
