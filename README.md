<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Project Setup

1. Copy environment file:
   ```bash
   cp .env.example .env
   ```
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```
3. Generate application key:
   ```bash
   php artisan key:generate
   ```
4. Run migrations and seeders:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```
5. Start the application:
   ```bash
   php artisan serve
   ```

## What is implemented

- Laravel 12 application with a Livewire purchase entry module.
- Dynamic purchase rows with item, brand, quantity, and price.
- Alpine.js reactive total calculation using Livewire entangle.
- Role-based access control with Admin and User roles.
- Legacy data import with idempotent duplicate prevention.
- Secure PHP MySQLi bugfix example.

## Usage

- Login at `/login`.
- Open the purchase module at `/purchases`.
- Admin users can create, edit, delete purchases and import legacy data.
- Regular users can only view the purchase list.

## Seeded users

- Admin: `admin@example.com` / `password`
- User: `user@example.com` / `password`

## Legacy import

The legacy import is implemented in `database/seeders/LegacyPurchaseSeeder.php`.
It maps legacy `item_name` and `brand_name` into normalized tables and inserts purchases without duplicates.

Run manually:

```bash
php artisan db:seed --class=LegacyPurchaseSeeder
```

## PHP MySQLi bug fix

See `legacy-mysqli-fix.php` for the corrected secure version.

## Assumptions

- User roles are stored in `users.role`.
- Admin role can manage data and run legacy import.
- User role can only view purchases.
- No hardcoded IDs are used in business logic.
- Validation is performed on Livewire input and server-side requests.

## License

The application is provided under the MIT license.
