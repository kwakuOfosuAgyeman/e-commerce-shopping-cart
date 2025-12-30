# E-Commerce Shopping Cart

A modern, full-featured e-commerce platform built with Laravel 12, Livewire 3, and Tailwind CSS.

## Features

### Customer Features
- **Product Browsing** - Browse products with filtering by category, brand, price range
- **Product Search** - Real-time search with caching for performance
- **Shopping Cart** - Add/remove items, update quantities with stock validation
- **Checkout Flow** - Streamlined checkout with order confirmation emails
- **Order Management** - View order history, track orders, cancel pending orders
- **User Authentication** - Registration, login, email verification

### Technical Highlights
- **Service Layer Architecture** - Clean separation of business logic
- **Livewire Components** - Real-time UI updates without page reloads
- **Database Transactions** - Atomic operations with rollback on failure
- **Pessimistic Locking** - Race condition prevention for stock management
- **Queued Jobs** - Background processing for emails and reports
- **Model Observers** - Automated low-stock notifications
- **Enum-Based States** - Type-safe order/payment status management
- **Security Headers** - CSP, HSTS, XSS protection middleware

## Requirements

- PHP 8.2+
- Composer 2.x
- Node.js 18+ & NPM
- SQLite (default) or MySQL/PostgreSQL

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/kwakuOfosuAgyeman/e-commerce-shopping-cart
cd e-commerce-shopping-cart
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup

**Option A: SQLite (Default)**

```bash
touch database/database.sqlite
php artisan migrate
```

**Option B: MySQL/PostgreSQL**

Update `.env` with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce
DB_USERNAME=root
DB_PASSWORD=your_password
```

Then run migrations:

```bash
php artisan migrate
```

### 5. Seed Demo Data

```bash
php artisan db:seed
```

This creates:
| User | Email | Password | Role |
|------|-------|----------|------|
| Admin User | admin@example.com | password | Admin |
| Test User | test@example.com | password | Customer |

### 6. Build Frontend Assets

```bash
npm run build
```

### 7. Start the Application

**Quick Start (All Services):**

```bash
composer dev
```

This starts concurrently:
- Laravel development server (http://localhost:8000)
- Queue worker for background jobs
- Log viewer (Laravel Pail)
- Vite dev server for hot reloading

**Or Start Services Individually:**

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Queue worker (for emails)
php artisan queue:work

# Terminal 3: Vite (for asset hot-reloading in development)
npm run dev
```


## Configuration

### Email (Required for Order Confirmations)

For development, emails are logged to `storage/logs/laravel.log` by default.

Configure SMTP in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=orders@yourstore.com
MAIL_FROM_NAME="Your Store"
```

### Queue Worker (Required for Background Jobs)

The application uses database queues. Ensure the queue worker is running:

```bash
php artisan queue:work
```

## Security Features

| Feature | Implementation |
|---------|----------------|
| Password Policy | Min 8 chars, mixed case, numbers, symbols, breach check |
| CSRF Protection | Laravel default on all forms |
| XSS Prevention | Blade escaping + CSP headers |
| SQL Injection | Eloquent ORM + prepared statements |
| Clickjacking | X-Frame-Options: DENY |
| HTTPS Enforcement | ForceHttps middleware in production |
| Rate Limiting | Throttle middleware on sensitive routes |
| Input Validation | Dedicated Request classes with sanitization |

## API Endpoints

### Public
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | Homepage with featured products |
| GET | `/products` | Product listing with filters |
| GET | `/products/{slug}` | Product detail page |
| GET | `/search/products` | Search API (throttled: 60/min) |

### Authenticated
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/cart` | View shopping cart |
| GET | `/checkout` | Checkout page |
| POST | `/order/place` | Place order |
| GET | `/my-orders` | Order history |
| GET | `/order/track/{id}` | Track specific order |
| PUT | `/order/cancel/{id}` | Cancel order |

## Testing

```bash
# Run all tests
php artisan test

# Or with composer
composer test
```

## Common Issues

### "Class not found" errors
```bash
composer dump-autoload
```

### Queue jobs not processing
Ensure queue worker is running:
```bash
php artisan queue:work
```

### Styles not loading
Rebuild assets:
```bash
npm run build
```

### Database errors after pulling changes
```bash
php artisan migrate:fresh --seed
```
