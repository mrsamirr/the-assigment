# Appointment Booking System

A full-stack appointment booking system built with Laravel 10 and Vue 3. Users can register, login, book appointments with specific time slots, view their appointments, and cancel them.

## Tech Stack

- **Backend**: PHP 8.1+ with Laravel 10
- **Frontend**: Vue 3 (Composition API) + Vite
- **Styling**: Tailwind CSS v3
- **HTTP Client**: Axios
- **Authentication**: JWT (tymon/jwt-auth)
- **Database**: MySQL 8
- **State Management**: Pinia

## Local Setup

### Prerequisites

- PHP 8.1+
- Composer
- Node.js 18+
- MySQL 8

### Backend Setup

1. Navigate to the backend directory:
   ```bash
   cd backend
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Copy environment file:
   ```bash
   cp .env.example .env
   ```

4. Configure your database in `.env`:
   ```
   DB_DATABASE=appointment_booking
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

5. Generate application key:
   ```bash
   php artisan key:generate
   ```

6. Generate JWT secret:
   ```bash
   php artisan jwt:secret
   ```

7. Run migrations:
   ```bash
   php artisan migrate
   ```

8. Start the development server:
   ```bash
   php artisan serve
   ```

The API will be available at `http://localhost:8000`

### Frontend Setup

1. Navigate to the frontend directory:
   ```bash
   cd frontend
   ```

2. Install dependencies:
   ```bash
   npm install
   ```

3. Copy environment file:
   ```bash
   cp .env.example .env
   ```

4. Start the development server:
   ```bash
   npm run dev
   ```

The frontend will be available at `http://localhost:5173`

## Environment Variables

### Backend (.env)

| Variable | Description | Default |
|----------|-------------|---------|
| APP_NAME | Application name | Appointment Booking System |
| APP_ENV | Environment (local/production) | local |
| APP_KEY | Application encryption key | Generated via artisan |
| APP_DEBUG | Debug mode | true |
| APP_URL | Backend URL | http://localhost:8000 |
| DB_CONNECTION | Database driver | mysql |
| DB_HOST | Database host | 127.0.0.1 |
| DB_PORT | Database port | 3306 |
| DB_DATABASE | Database name | appointment_booking |
| DB_USERNAME | Database username | root |
| DB_PASSWORD | Database password | |
| JWT_SECRET | JWT signing secret | Generated via artisan |

### Frontend (.env)

| Variable | Description | Default |
|----------|-------------|---------|
| VITE_API_URL | Backend API base URL | http://localhost:8000/api/v1 |

## API Endpoints

Base URL: `/api/v1`

### Authentication

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | /auth/register | Register new user | No |
| POST | /auth/login | Login user | No |
| POST | /auth/logout | Logout user | Yes |

### User

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | /user/profile | Get current user profile | Yes |

### Appointments

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | /appointments | List user's appointments | Yes |
| POST | /appointments | Create new appointment | Yes |
| DELETE | /appointments/{id} | Cancel an appointment | Yes |

### Response Format

All API responses follow this format:
```json
{
  "success": true | false,
  "data": {} | [] | null,
  "message": "string"
}
```

### HTTP Status Codes

- 200: OK
- 201: Created
- 400: Bad Request
- 401: Unauthorized
- 409: Conflict (duplicate slot)
- 422: Validation Error
- 500: Server Error

## Features

- User registration with validation
- User login with JWT authentication
- Protected routes requiring authentication
- View all appointments on dashboard
- Book new appointments with:
  - Title (required)
  - Date (required, must be today or future)
  - Time (required, 30-minute slots from 09:00 to 18:00)
  - Notes (optional)
- Duplicate slot prevention (same user, date, and time)
- Cancel appointments (sets status to cancelled)
- Status badges (pending, confirmed, cancelled) with color coding
- Responsive design with Tailwind CSS
- Form validation (frontend and backend)
- Auth guard for protected routes
- Auto-redirect on 401 errors
