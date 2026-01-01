# Task Manager API

A professional RESTful API built with Laravel for managing tasks with user authentication, role-based access control (RBAC), and comprehensive permission management.

## Features

- 🔐 **User Authentication** - Secure registration and login using Laravel Sanctum
- 👥 **Role-Based Access Control** - Admin and User roles with granular permissions
- ✅ **Task Management** - Complete CRUD operations for tasks
- 🔒 **Permission System** - Fine-grained access control using Spatie Laravel Permission
- 📝 **Task Attributes** - Title, description, status, and due date tracking
- 🛡️ **Security** - Token-based authentication and authorization middleware
- 📊 **Enhanced Pagination** - Structured pagination with metadata and navigation links
- ✨ **API Resources** - Consistent, structured JSON responses
- 🔍 **Advanced Validation** - Form Request classes with custom error messages
- 📈 **Smart Task Tracking** - Automatic overdue detection and days remaining calculation

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
  "success": true,
  "message": "User registered successfully",
  "token": "1|AbCdEf...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2025-12-30 10:00:00",
    "updated_at": "2025-12-30 10:00:00"
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
  "success": true,
  "message": "Login successful",
  "token": "2|GhIjKl...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "created_at": "2025-12-30 10:00:00",
    "updated_at": "2025-12-30 10:00:00"
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
  "success": true,
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
{"data": [
    {
      "id": 1,
      "title": "Complete project",
      "description": "Finish the API development",
      "status": "pending",
      "due_date": "2025-12-31",
      "is_overdue": false,
      "days_remaining": 1,
      "created_at": "2025-12-30 10:00:00",
      "updated_at": "2025-12-30 10:00:00"
    }
  ],
  "meta": {
    "total": 1,
    "count": 1,
    "per_page": 10,
    "current_page": 1,
    "total_pages": 1
  },
  "links": {
    "first": "http://127.0.0.1:8000/api/tasks?page=1",
    "last": "http://127.0.0.1:8000/api/tasks?page=1",
    "prev": null,
    "next": null
  }
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
  "data": {
    "id": 1,
    "title": "New Task",
    "description": "Task description",
    "status": "pending",
    "due_date": "2025-12-31",
    "is_overdue": false,
    "days_remaining": 1,
    "created_at": "2025-12-30 10:00:00",
    "updated_at": "2025-12-30 10:00:00"
  }
}
```

#### Get a Single Task
```http
GET /api/tasks/{id}
```

**Response:**
```json
{
  "data": {
    "id": 1,
    "title": "Complete project",
    "description": "Finish the API development",
    "status": "pending",
    "due_date": "2025-12-31",
    "is_overdue": false,
    "days_remaining": 1,
    "created_at": "2025-12-30 10:00:00",
    "updated_at": "2025-12-30 10:00:00"
  }
}
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

**Response:**
```json
{
  "data": {
    "id": 1,
    "title": "Updated Title",
    "description": "Task description",
    "status": "in_progress",
    "due_date": "2025-12-31",
    "is_overdue": false,
    "days_remaining": 1,
    "created_at": "2025-12-30 10:00:00",
    "updated_at": "2025-12-30 11:30:00"
  }
}
```

#### Delete a Task
```http
DELETE /api/tasks/{id}
```

**Response:**
```json
{
  "success": true,
  "message": "Task deleted successfully"
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
- `email` - required, valid email, unique in users table
- `password` - required, min 8 characters, confirmed

### Login
- `email` - required, valid email format
- `password` - required, string

### Task Creation
- `title` - required, string, max 255 characters
- `description` - optional, string, max 1000 characters
- `status` - optional, enum: `pending`, `in_progress`, `completed`
- `due_date` - optional, date format, must be today or later

### Task Update
- `title` - optional, string, max 255 characters
- `description` - optional, string, max 1000 characters
- `status` - optional, enum: `pending`, `in_progress`, `completed`
- `due_date` - optional, date format, must be today or later

### Custom Validation Messages
All validation errors return helpful messages in Arabic:
- Required field: "حقل {field} مطلوب" (Field {field} is required)
- Max length: "حقل {field} يجب ألا يتجاوز {max} حرف" (Field {field} must not exceed {max} characters)
- Invalid status: "حالة المهمة يجب أن تكون: pending أو in_progress أو completed"
- Date validation: "يجب أن يكون تاريخ الاستحقاق اليوم أو في المستقبل"

## API Resources

### UserResource
Transforms user data with the following structure:
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "roles": ["user"],
  "permissions": ["view tasks", "create tasks", "edit tasks", "delete tasks"],
  "created_at": "2025-12-30 10:00:00",
  "updated_at": "2025-12-30 10:00:00"
}
```

### TaskResource
Transforms task data with computed fields:
```json
{
  "id": 1,
  "title": "Complete project",
  "description": "Finish the API development",
  "status": "pending",
  "due_date": "2025-12-31",
  "is_overdue": false,
  "days_remaining": 1,
  "created_at": "2025-12-30 10:00:00",
  "updated_at": "2025-12-30 10:00:00"
}
```

**Computed Fields:**
- `is_overdue` - Boolean indicating if task is overdue
- `days_remaining` - Integer showing days until due date (negative if overdue)

### TaskCollection
Transforms paginated task lists with meta and links:
```json
{
  "data": [...],
  "meta": {
    "total": 100,
    "count": 10,
    "per_page": 10,
    "current_page": 1,
    "total_pages": 10
  },
  "links": {
    "first": "http://127.0.0.1:8000/api/tasks?page=1",
    "last": "http://127.0.0.1:8000/api/tasks?page=10",
    "prev": null,
    "next": "http://127.0.0.1:8000/api/tasks?page=2"
  }
}
```

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
# Register a new user
$registerResponse = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/auth/register" `
  -Method Post -ContentType "application/json" `
  -Body '{"name":"Test User","email":"test@example.com","password":"password123","password_confirmation":"password123"}'
Write-Host "User registered: $($registerResponse.data.name)"

# Login and save token
$loginResponse = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/auth/login" `
  -Method Post -ContentType "application/json" `
  -Body '{"email":"test@example.com","password":"password123"}'
$token = $loginResponse.data.token
Write-Host "Token: $token"

# Get all tasks (paginated)
$headers = @{ "Authorization" = "Bearer $token"; "Accept" = "application/json" }
$tasksResponse = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/tasks" -Method Get -Headers $headers
Write-Host "Total tasks: $($tasksResponse.meta.total)"

# Create a new task
$headers = @{ "Authorization" = "Bearer $token"; "Content-Type" = "application/json" }
$newTask = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/tasks" -Method Post -Headers $headers `
  -Body '{"title":"My Task","description":"Complete API testing","status":"pending","due_date":"2025-12-31"}'
Write-Host "Task created with ID: $($newTask.data.id)"
Write-Host "Days remaining: $($newTask.data.days_remaining)"

# Update the task
$taskId = $newTask.data.id
$updatedTask = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/tasks/$taskId" `
  -Method Put -Headers $headers `
  -Body '{"status":"in_progress"}'
Write-Host "Task updated: $($updatedTask.data.status)"

# Delete the task
$deleteResponse = Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/tasks/$taskId" `
  -Method Delete -Headers $headers
Write-Host $deleteResponse.message
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
│   │   ├── Requests/
│   │   │   ├── RegisterRequest.php
│   │   │   ├── LoginRequest.php
│   │   │   ├── StoreTaskRequest.php
│   │   │   └── UpdateTaskRequest.php
│   │   ├── Resources/
│   │   │   ├── UserResource.php
│   │   │   ├── TaskResource.php
│   │   │   └── TaskCollection.php
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
    ├── permission.php
    └── sanctum.php
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

Developed with Ahmed Maher ❤️ using Laravel

---

**Note:** This is a learning/demonstration project. For production use, consider implementing additional features like email verification, password reset, rate limiting, and comprehensive testing.