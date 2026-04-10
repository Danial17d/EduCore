# EduCore

EduCore is a Laravel-based learning platform for managing courses, lessons, instructors, enrollments, and student progress.

## Features

- User authentication and email verification
- Roles: admin, instructor, student
- Course and lesson management
- Student enrollment
- Lesson progress tracking
- Profile management

## Tech Stack

- Laravel 12
- PHP 8.2+
- Blade
- Vite
- Tailwind CSS
- Pest

## Setup

1. Create `.env` from `.env.example`
2. Install dependencies
3. Run migrations and seeders
4. Start the app

```bash
composer install
npm install
php artisan migrate --seed
composer run dev
cp public/assets/default.jpg storage/app/public/default.jpg
php artisan storage:link
```

## Demo Login

- Email: `test@example.com`
- Password: `password`

## Notes

- Uploaded files use Laravel storage
- Main public page: `/courses`
- Main app page: `/dashboard`
