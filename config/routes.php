<?php

// Authentication Routes
$router->post('/register', 'RegisterController@register');
$router->post('/login', 'LoginController@login');
$router->post('/logout', 'LoginController@logout', 'LoginMiddleware');
$router->post('/refresh-token', 'AuthController@refreshToken');

// Email verification
$router->get('/verify-email', 'VerifyController@verifyEmail');

// Password reset
$router->post('/request-password-reset', 'PasswordResetController@requestReset');
$router->post('/reset-password', 'PasswordResetController@resetPassword');

// Post Routes (user-level)
$router->get('/posts', 'PostController@index', 'RateLimitMiddleware');
$router->get('/posts/{id}', 'PostController@show', 'RateLimitMiddleware');
$router->post('/posts', 'PostController@create', ['RateLimitMiddleware', 'LoginMiddleware']);
$router->put('/posts/{id}', 'PostController@update', ['RateLimitMiddleware', 'LoginMiddleware']);
$router->delete('/posts/{id}', 'PostController@delete', ['RateLimitMiddleware', 'LoginMiddleware']);

// Admin-only Routes
$router->get('/admin/dashboard', 'AdminController@dashboard', ['LoginMiddleware']);
