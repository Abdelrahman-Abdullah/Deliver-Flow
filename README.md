# DeliverFlow API 🚀

A multi-vendor real-time delivery tracking platform built with Laravel 11.

## Tech Stack

- **Laravel 12** — PHP Framework
- **MySQL 8** — Primary Database
- **Redis** — Cache, Queues & Location Storage
- **Laravel Reverb** — Real-time WebSocket Server
- **Laravel Sanctum** — API Authentication
- **Spatie Permission** — Roles & Permissions
- **Docker** — Containerized Infrastructure

## Features

- ✅ Multi-vendor support
- ✅ Real-time driver location tracking
- ✅ Role-based access control (super_admin, vendor, driver, customer)
- ✅ Arabic/English localization
- ✅ Order lifecycle management
- ✅ Database notifications
- ✅ Rate limiting
- ✅ Global JSON error handling

## Roles

| Role | Permissions |
|------|------------|
| super_admin | Full platform access |
| vendor | Manage own store, products & orders |
| driver | View assigned orders, update location |
| customer | Place orders, track delivery |

## Quick Start

### Prerequisites
- Docker Desktop
- Git

### Setup

```bash
# 1. Clone the repository
git clone https://github.com/YOUR_USERNAME/DeliverFlow.git
cd DeliverFlow

# 2. Copy environment file
cp .env.example .env

# 3. Start Docker containers
docker-compose up -d --build

# 4. Install dependencies
docker exec -it deliverflow_php composer install

# 5. Generate app key
docker exec -it deliverflow_php php artisan key:generate

# 6. Run migrations
docker exec -it deliverflow_php php artisan migrate

# 7. Seed database
docker exec -it deliverflow_php php artisan db:seed

# 8. Visit the API
http://localhost:8000/api
```

### Docker Services

| Service | Port | Purpose |
|---------|------|---------|
| nginx | 8000 | Web Server |
| php | 9000 | PHP-FPM |
| mysql | 3307 | Database |
| redis | 6379 | Cache & Queues |
| websockets | 6001 | Reverb WebSocket Server |
| queue | — | Queue Worker |

## API Endpoints

### Auth
```
POST   /api/auth/register
POST   /api/auth/login
POST   /api/auth/logout
GET    /api/auth/me
```

### Vendors
```
GET    /api/vendors
GET    /api/vendors/{id}
POST   /api/vendors
PUT    /api/vendors/{id}
DELETE /api/vendors/{id}
```

### Products
```
GET    /api/vendors/{id}/products
GET    /api/products/{id}
POST   /api/products
PUT    /api/products/{id}
DELETE /api/products/{id}
```

### Categories
```
GET    /api/categories
GET    /api/categories/{id}
POST   /api/categories
PUT    /api/categories/{id}
DELETE /api/categories/{id}
```

### Orders
```
GET    /api/orders
POST   /api/orders
GET    /api/orders/{id}
PATCH  /api/orders/{id}/status
PATCH  /api/orders/{id}/assign-driver
DELETE /api/orders/{id}
```

### Location Tracking
```
PATCH  /api/driver/location
GET    /api/orders/{id}/location
GET    /api/orders/{id}/location/history
GET    /api/orders/{id}/distance
```

### Notifications
```
GET    /api/notifications
GET    /api/notifications/unread
PATCH  /api/notifications/{id}/read
PATCH  /api/notifications/read-all
DELETE /api/notifications/{id}
```

## Test Credentials

| Role | Email | Password |
|------|-------|----------|
| super_admin | admin@deliverflow.com | password |
| vendor | vendor@deliverflow.com | password |
| driver | driver@deliverflow.com | password |
| customer | customer@deliverflow.com | password |

