# Events Management System

A robust Laravel-based Events Management System with features for event creation, management, reservations, and user roles.

## Features

- **Event Management**
  - Create, update, and delete events
  - Event categorization with event types
  - Location management with address details
  - Image upload support for events and locations
  - Event status tracking (draft, published, cancelled, completed)
  - Featured events functionality

- **Reservation System**
  - Create and manage reservations
  - Track reservation status (pending, confirmed, cancelled, completed)
  - Automatic price calculation
  - Capacity management
  - Reservation history

- **User Management**
  - Role-based access control (Admin, Organizer, User)
  - Permission management using Spatie Permissions
  - User authentication with Laravel Sanctum
  - Profile management

- **Location Management**
  - Create and manage venues
  - Address management with geolocation
  - Image upload for locations
  - Capacity tracking

## Requirements

- PHP >= 8.1
- MySQL >= 5.7
- Composer
- Node.js & NPM
- Laravel >= 10.0

## Installation

1. Clone the repository:
```bash
git clone https://github.com/maryam-asha/EventManagementSystem
cd Events_Management_System
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install NPM dependencies:
```bash
npm install
```

4. Create environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your database in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=events_management
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Run migrations and seeders:
```bash
php artisan migrate --seed
```

8. Create storage link:
```bash
php artisan storage:link
```

9. Start the development server:
```bash
php artisan serve
```

## API Documentation

### Authentication

All API endpoints require authentication using Laravel Sanctum. Include the token in the Authorization header:
```
Authorization: Bearer your-token-here
```

### Event Endpoints

#### List Events
```http
GET /api/events
Query Parameters:
- status: Filter by status (draft, published, cancelled, completed)
- event_type: Filter by event type
- location: Filter by location
- upcoming: Filter upcoming events
- past: Filter past events
- sort_by: Sort field
- sort_direction: Sort direction (asc/desc)
```

#### Create Event
```http
POST /api/events
Content-Type: application/json

{
    "title": "Event Title",
    "description": "Event Description",
    "event_type_id": 1,
    "location_id": 1,
    "start_date": "01/01/2026",
    "end_date": "01/01/2027",
    "price": 1000,
    "capacity": 50,
    "is_published": true,
    "is_featured": false,
    "status": "draft",
    "images": [file1, file2]
}
```

#### Update Event
```http
PUT /api/events/{id}
Content-Type: application/json

{
    "title": "Updated Title",
    "description": "Updated Description",
    ...
}
```

#### Delete Event
```http
DELETE /api/events/{id}
```

### Reservation Endpoints

#### List Reservations
```http
GET /api/reservations
Query Parameters:
- status: Filter by status
- event: Filter by event
- upcoming: Filter upcoming reservations
- past: Filter past reservations
```

#### Create Reservation
```http
POST /api/reservations
Content-Type: application/json

{
    "event_id": 1,
    "quantity": 2,
    "notes": "Optional notes"
}
```

#### Cancel Reservation
```http
POST /api/reservations/{id}/cancel
Content-Type: application/json

{
    "reason": "Cancellation reason"
}
```

## Roles and Permissions

### Admin
- Full access to all features
- Manage users, roles, and permissions
- Manage all events and reservations

### Organizer
- Create and manage their own events
- View and manage event reservations
- Upload event images

### User
- View published events
- Create and manage their own reservations
- View their reservation history
