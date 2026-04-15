# IVAO CO Web

This is the project for the IVAO CO division website.

This project uses:

- Laravel 13.X
- Inertia 3
- Tailwind
- PHPStan

## Setup

1. Create the `.env` file.

```bash
cp .env.example .env
```

2. Set the IVAO credentials inside the `.env` files alongside the DB fields.

3. Run:

```bash
composer install
npm run install
php artisan migrate
php artisan typescript:transform
```

4. Start the Vite dev process

```bash
npm run dev
```
