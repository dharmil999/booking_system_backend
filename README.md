Booking System Backend

This repository contains the backend for a booking system built with Laravel 12.

Prerequisites

- PHP >= 8.2
- Composer
- MySQL or MariaDB (or any database supported by Laravel)
- Node.js & npm (for frontend asset building, optional for API-only use)
- (Optional) XAMPP if running locally on Windows

Quick Installation

1. Clone the repository:

```bash
git clone <repo-url>
cd booking_system_backend
```

2. Copy the example environment file and set your environment variables (database, app URL, etc.):

```powershell
copy .env.example .env
# Open .env and update DB_*, APP_URL and other settings
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

6. (Optional) Install npm packages and build assets:

```bash
npm install
# For development
npm run dev
# For production
npm run build
```

7. Start the local development server:

```bash
php artisan serve
# or use your local web server (XAMPP) and point the document root to the project's `public` folder
```

Running Tests

```bash
./vendor/bin/phpunit
# or
php artisan test
```

Common Commands

- `php artisan migrate:fresh --seed` — drop and re-run all migrations with seeders
- `php artisan tinker` — interact with the application from the command line
- `php artisan route:list` — list all routes

Troubleshooting

- If you see permission issues on storage or bootstrap/cache, run:

```bash
php artisan storage:link
```

- Ensure `APP_URL` and `DB_*` values in `.env` are correct for your environment.

Contributing

- Fork the repo, create a feature branch, and open a pull request.

License

- This project does not include a license file. Add one if you plan to publish.

If you want, I can also add a badge, example `.env` values, or more detailed setup for XAMPP. Which would you like next?