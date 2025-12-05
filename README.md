Booking System Backend

This repository contains the backend for a booking system built with Laravel 12.

Prerequisites

- PHP >= 8.2
- Composer
- MySQL or MariaDB (or any database supported by Laravel)

Quick Installation

1. Clone the repository:

```bash
git clone <repo-url>
cd booking_system_backend
```

2. Create a copy of the example environment file and set your environment variables (database, app URL, etc.).

```bash
cp .env.example .env
# then open `.env` and update DB_*, APP_URL and other settings
```

3. Install PHP dependencies with Composer:

```bash
composer install
```

4. Generate application key:

```bash
php artisan key:generate
```

5. Run migrations and seeders to create the database schema and sample data:

```bash
php artisan migrate --seed
```

6. Start the local development server:

```bash
php artisan serve
```

Below things is not completed

#1. I also wanted to install vue within laravel project but for now i have done seperatey.