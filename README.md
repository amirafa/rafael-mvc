
# Rafael PHP MVC Framework with ORM

## 📖 Overview

**Rafael** is a lightweight **Laravel-inspired PHP MVC framework** designed for building modern, API-first web applications. It includes:

✅ Lightweight MVC structure  
✅ Built-in ORM for database interactions  
✅ JWT Authentication (with **user/admin roles**)  
✅ Secure **Refresh Token system**  
✅ Email Verification system  
✅ Password Reset with token system  
✅ Rate Limiting Middleware  
✅ Role-Based Access Control (RBAC)  
✅ Full CRUD Example (Posts)  

---

## 📂 Directory Structure

```
rafael/
├── app/
│   ├── controllers/
│   │   ├── AdminController.php          # Admin dashboard
│   │   ├── AuthController.php            # Refresh token handler
│   │   ├── LoginController.php           # Login handling
│   │   ├── PostController.php            # Full Post CRUD
│   │   ├── RegisterController.php        # Register user + email verification
│   │   ├── VerifyController.php          # Email verification handler
│   │   ├── PasswordResetController.php   # Password reset handler
│   ├── models/
│   │   ├── Post.php                      # Post model using BaseModel (ORM)
│   │   ├── BaseModel.php                 # Reusable ORM base for all models
├── config/
│   ├── database.php                      # Database credentials
│   ├── routes.php                        # All application routes
├── core/
│   ├── Controller.php                    # Base controller (JSON responses)
│   ├── Database.php                      # Database wrapper (PDO)
│   ├── Model.php                         # (Unused - BaseModel replaces it)
│   ├── Router.php                        # Router (handles URL dispatching)
│   ├── helpers/
│   │   ├── JwtHelper.php                 # JWT creation & validation
│   ├── middleware/
│   │   ├── LoginMiddleware.php           # JWT auth enforcement
│   │   ├── RateLimitMiddleware.php       # Simple rate limiter
│   │   ├── RoleMiddleware.php            # Role-based route protection
├── public/
│   ├── index.php                         # Main entry point
├── storage/
│   ├── users.json                        # User database (simple JSON store)
│   ├── refresh_tokens.json               # Active refresh tokens
│   ├── password_reset_tokens.json        # Active password reset tokens
```

---

## ⚙️ Installation

1. Clone this repository into your web server (e.g., `htdocs` for XAMPP).
2. Import your database if you're using Posts.
3. Edit `config/database.php` to match your database credentials.
4. Ensure the `storage/` folder is writable by your web server.
5. Serve the project using:

```bash
php -S localhost:8000 -t public
```

---

## 🔗 Routes

### Authentication & User Management

| Method | Route | Description |
|---|---|---|
| POST | `/register` | Register new user (email verification sent) |
| POST | `/login` | Login & get JWT + refresh token |
| POST | `/logout` | Logout (invalidate refresh token) |
| POST | `/refresh-token` | Refresh JWT using refresh token |
| GET | `/verify-email` | Verify email (via token) |
| POST | `/request-password-reset` | Request password reset link |
| POST | `/reset-password` | Reset password (via token) |

### Posts (CRUD Example)

| Method | Route | Description |
|---|---|---|
| GET | `/posts` | List all posts (rate-limited) |
| GET | `/posts/{id}` | View single post (rate-limited) |
| POST | `/posts` | Create new post (auth + rate-limited) |
| PUT | `/posts/{id}` | Update post (auth + rate-limited) |
| DELETE | `/posts/{id}` | Delete post (auth + rate-limited) |

### Admin Routes

| Method | Route | Description |
|---|---|---|
| GET | `/admin/dashboard` | Admin-only dashboard (auth + admin role check) |

---

## 🧰 Built-in ORM (BaseModel)

All models that extend `BaseModel` automatically get:

| Method | Description |
|---|---|
| `create(array $data)` | Insert new record |
| `getAll()` | Fetch all records |
| `findById($id)` | Fetch record by ID |
| `update($id, array $data)` | Update record by ID |
| `delete($id)` | Delete record by ID |

---

## 🔐 Authentication Flow

1️⃣ **Registration**  
- User registers at `/register`.
- Receives **verification link**.
- Cannot log in until email is verified.

2️⃣ **Login**  
- User logs in at `/login`.
- Receives `access_token` & `refresh_token`.

3️⃣ **Access Protected Routes**  
- Provide `Authorization: Bearer {access_token}`.

4️⃣ **Refresh Token**  
- Use `/refresh-token` when the access token expires.

---

## 📧 Email Verification Flow

1️⃣ User registers (gets verification link)  
2️⃣ User visits `/verify-email?token=xxxxx`  
3️⃣ Email marked verified ✅  

---

## 🔒 Password Reset Flow

1️⃣ User requests reset at `/request-password-reset`.  
2️⃣ System returns a **reset link**.  
3️⃣ User submits new password to `/reset-password` with token.  
4️⃣ Password updated ✅  

---

## ⚠️ Middleware

| Middleware | Description |
|---|---|
| **LoginMiddleware** | Ensures valid JWT token is present |
| **RateLimitMiddleware** | Limits request rate per IP |
| **RoleMiddleware** | Ensures required role (admin/user) |

---

## 📊 Example Table (Posts)

```sql
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    body TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 🚀 Quick Start

1. Start the server:
    ```bash
    php -S localhost:8000 -t public
    ```
2. Visit:
    ```
    http://localhost:8000
    ```

