# Eventix Setup Guide

Follow these steps to fully configure the application:

```bash
# 1. Create project (Assume already initialized, proceed to step 2/3 if needed)
composer install

# 2. Install Jetstream with Livewire
composer require laravel/jetstream
php artisan jetstream:install livewire

# 3. Install Volt and Sanctum API
composer require livewire/volt
php artisan volt:install
php artisan install:api

# 4. Configure .env
# Edit your .env file to match MySQL details
# DB_CONNECTION=mysql
# DB_DATABASE=eventix
# DB_USERNAME=root
# DB_PASSWORD=your_password

# 5. Build assets
npm install && npm run build

# 6. Generate key, migrate, seed
php artisan key:generate
php artisan migrate:fresh --seed

# 7. Run
php artisan serve
npm run dev

# Demo credentials:
# admin@eventix.com / password
# organiser@eventix.com / password
# customer@eventix.com / password
```
