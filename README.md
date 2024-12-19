# Laravel Filament Admin Dashboard

A modern admin dashboard built with Laravel 11 and Filament 3, featuring a comprehensive set of tools and plugins for rapid application development.

## 🚀 Features

-   Built on Laravel 11
-   Filament 3 Admin Panel
-   Multi-language Support
-   Advanced User Management
-   Role-Based Access Control (via filament-shield)
-   Media Library Integration
-   System Health Monitoring
-   Exception Tracking
-   Settings Management Hub
-   Activity Auditing
-   Performance Monitoring (Laravel Pulse)

## 📋 Requirements

-   PHP 8.2 or higher
-   Node.js & NPM
-   Composer
-   MySQL/PostgreSQL

## 🛠️ Installation

1. Clone the repository:

```bash
git clone https://github.com/moataz-01/filament-admin-dashboard.git
cd <project-folder>
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

6. Configure your database in `.env` file:

```console
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Run migrations:

```bash
php artisan migrate
```

8. Build assets:

```bash
npm run build
```

## 🚀 Development

To start the development server:

```bash
npm run dev
```

This will run the following services concurrently:

-   Laravel development server
-   Vite asset compilation
-   Queue worker

## 📦 Included Packages

### Filament Plugins

-   filament-exceptions
-   filament-language-switch
-   filament-shield
-   filament-spatie-laravel-media-library-plugin
-   filament-spatie-laravel-translatable-plugin
-   filament-environment-indicator
-   filament-settings-hub

### Laravel Packages

-   Laravel Pulse
-   Laravel Auditing
-   Spatie Permission
-   Spatie Sluggable

## 🌐 Localization

The application supports multiple languages including:

-   English
-   Arabic
-   German
-   French
-   Spanish
-   And many more...

## 🔒 Security

The application implements:

-   Role-based access control
-   Activity auditing
-   Exception tracking
-   Health monitoring

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 🙏 Acknowledgments

-   [Laravel](https://laravel.com)
-   [Filament](https://filamentphp.com)
-   All contributors and package maintainers
