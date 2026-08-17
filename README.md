# IVAO CO Web

This is the project for the IVAO CO division website.

This project uses:

- Laravel 13.X
- Inertia 3
- Vue 3
- Tailwind 4
- PHPStan
- RectorPHP

For internationalization (i18n) please refer to [laravel-vue-i18n](github.com/xico2k/laravel-vue-i18n)
For permissions please refer to [larave-permission](https://spatie.be/docs/laravel-permission/v7/basic-usage/basic-usage)

## Requirements

- PHP 8.4
- Node v24.12.0
- Mysql 8.x

## Setup

1. Create the `.env` file.

```bash
cp .env.example .env
```

2. Set the IVAO credentials inside the `.env` files alongside the DB fields.

3. Run:

```bash
composer install
npm install
php artisan migrate --seed
php artisan ivao:fetch-atc-positions
php artisan ivao:fetch-atc-position-fras
php artisan typescript:transform
```

4. Start the Vite dev process

```bash
npm run dev
```
