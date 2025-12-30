# Task Manager API

A professional RESTful API built with Laravel for managing tasks with user authentication, role-based access control (RBAC), and comprehensive permission management.

## Features

- 🔐 **User Authentication** - Secure registration and login using Laravel Sanctum
- 👥 **Role-Based Access Control** - Admin and User roles with granular permissions
- ✅ **Task Management** - Complete CRUD operations for tasks
- 🔒 **Permission System** - Fine-grained access control using Spatie Laravel Permission
- 📝 **Task Attributes** - Title, description, status, and due date tracking
- 🛡️ **Security** - Token-based authentication and authorization middleware
- 📊 **Pagination** - Efficient data retrieval with paginated responses

## Tech Stack

- **Framework:** Laravel 10.x
- **Authentication:** Laravel Sanctum
- **Authorization:** Spatie Laravel Permission
- **Database:** MySQL
- **Language:** PHP 8.1+

## Requirements

- PHP >= 8.1
- Composer
- MySQL >= 5.7 or MariaDB >= 10.3
- Apache/Nginx web server

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/task-manager-api.git
cd task-manager-api
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Configuration

Copy the example environment file and configure your database:

```bash
cp .env.example .env
```

Edit `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Seed Roles and Permissions

```bash
php artisan db:seed --class=RolePermissionSeeder
```

This will create:
- **Roles:** `admin`, `user`
- **Permissions:** `view tasks`, `create tasks`, `edit tasks`, `delete tasks`, `manage users`

### 7. Start Development Server

```bash
php artisan serve
```

The API will be available at `http://127.0.0.1:8000`

## API Documentation

### Authentication Endpoints

#### Register a New User
```http
POST /api/auth/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response:**
```json
{
  "token": "1|AbCdEf...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  }
}
```

#### Login
```http
POST /api/auth/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "token": "2|GhIjKl...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  }
}
```

#### Logout
```http
POST /api/auth/logout
Authorization: Bearer {token}
```

**Response:**
```json
{
  "message": "Logged out successfully"
}
```

### Task Endpoints

All task endpoints require authentication. Include the token in the Authorization header:
```
Authorization: Bearer {your-token}
```

#### Get All Tasks
```http
GET /api/tasks
```

**Response:**
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "title": "Complete project",
      "description": "Finish the API development",
      "status": "pending",
      "due_date": "2025-12-31",
      "created_at": "2025-12-30T10:00:00.000000Z",
      "updated_at": "2025-12-30T10:00:00.000000Z"
    }
  ],
  "per_page": 10,
  "total": 1
}
```

#### Create a Task
```http
POST /api/tasks
Content-Type: application/json

{
  "title": "New Task",
  "description": "Task description",
  "status": "pending",
  "due_date": "2025-12-31"
}
```

**Response:**
```json
{
  "id": 1,
  "user_id": 1,
  "title": "New Task",
  "description": "Task description",
  "status": "pending",
  "due_date": "2025-12-31",
  "created_at": "2025-12-30T10:00:00.000000Z",
  "updated_at": "2025-12-30T10:00:00.000000Z"
}
```

#### Get a Single Task
```http
GET /api/tasks/{id}
```

#### Update a Task
```http
PUT /api/tasks/{id}
Content-Type: application/json

{
  "title": "Updated Title",
  "status": "in_progress"
}
```

#### Delete a Task
```http
DELETE /api/tasks/{id}
```

**Response:**
```json
{
  "message": "Task deleted"
}
```

## Roles and Permissions

### User Role (Default)
New users automatically receive the `user` role with the following permissions:
- ✅ `view tasks` - View own tasks
- ✅ `create tasks` - Create new tasks
- ✅ `edit tasks` - Edit own tasks
- ✅ `delete tasks` - Delete own tasks

### Admin Role
Admin users have all permissions including:
- ✅ All user permissions
- ✅ `manage users` - Manage system users

### Assigning Admin Role

To promote a user to admin, use Laravel Tinker:

```bash
php artisan tinker
```

```php
$user = App\Models\User::find(1); // Replace with user ID
$user->assignRole('admin');
```

## Validation Rules

### Registration
- `name` - required, string, max 255 characters
- `email` - required, valid email, unique
- `password` - required, min 6 characters, confirmed

### Login
- `email` - required, valid email
- `password` - required

### Task Creation/Update
- `title` - required on create, max 255 characters
- `description` - optional, string
- `status` - optional, one of: `pending`, `in_progress`, `completed`
- `due_date` - optional, valid date, must be today or later

## Error Handling

The API returns appropriate HTTP status codes:

- `200 OK` - Successful GET, PUT, DELETE
- `201 Created` - Successful POST
- `400 Bad Request` - Validation errors
- `401 Unauthorized` - Missing or invalid token
- `403 Forbidden` - Insufficient permissions
- `404 Not Found` - Resource not found
- `500 Internal Server Error` - Server error

## Testing with PowerShell

```powershell
# Register
Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/auth/register" `
  -Method Post -ContentType "application/json" `
  -Body '{"name":"Test User","email":"test@example.com","password":"password123","password_confirmation":"password123"}'

# Login and save token
$response = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/auth/login" `
  -Method Post -ContentType "application/json" `
  -Body '{"email":"test@example.com","password":"password123"}'
$token = $response.token

# Get tasks
$headers = @{ "Authorization" = "Bearer $token"; "Accept" = "application/json" }
Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/tasks" -Method Get -Headers $headers

# Create task
$headers = @{ "Authorization" = "Bearer $token"; "Content-Type" = "application/json" }
Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/tasks" -Method Post -Headers $headers `
  -Body '{"title":"My Task","description":"Description","status":"pending","due_date":"2025-12-31"}'
```

## Database Schema

### Users Table
- `id` - Primary key
- `name` - User full name
- `email` - Unique email address
- `password` - Hashed password
- `created_at`, `updated_at` - Timestamps

### Tasks Table
- `id` - Primary key
- `user_id` - Foreign key to users
- `title` - Task title
- `description` - Task description (nullable)
- `status` - Task status (default: pending)
- `due_date` - Due date (nullable)
- `created_at`, `updated_at` - Timestamps

### Roles & Permissions Tables
Managed by Spatie Laravel Permission package:
- `roles` - Available roles
- `permissions` - Available permissions
- `model_has_roles` - User-role assignments
- `model_has_permissions` - Direct user permissions
- `role_has_permissions` - Role-permission assignments

## Project Structure

```
task-manager-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       ├── AuthentController.php
│   │   │       └── TaskController.php
│   │   └── Kernel.php
│   └── Models/
│       ├── User.php
│       └── Task.php
├── database/
│   ├── migrations/
│   │   ├── create_users_table.php
│   │   ├── create_tasks_table.php
│   │   └── create_permission_tables.php
│   └── seeders/
│       └── RolePermissionSeeder.php
├── routes/
│   └── api.php
└── config/
    └── permission.php
```

## Security Considerations

- All passwords are hashed using bcrypt
- API tokens are managed by Laravel Sanctum
- CORS is properly configured
- Middleware protects all sensitive routes
- Authorization checks prevent unauthorized access
- Input validation prevents injection attacks

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## Support

For issues and questions, please open an issue on GitHub.

## Author

Developed with ❤️ using Laravel

---

**Note:** This is a learning/demonstration project. For production use, consider implementing additional features like email verification, password reset, rate limiting, and comprehensive testing.