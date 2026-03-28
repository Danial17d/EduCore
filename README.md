# EduCore

EduCore is a Laravel 12 learning management application for managing courses, lessons, enrollments, instructors, and learner progress. It includes authentication, email verification, role-based access control, profile management, and media uploads for course and lesson content.

## Stack

- PHP 8.2+
- Laravel 12
- Laravel Breeze
- Blade templates
- Vite
- Tailwind CSS 4
- Alpine.js
- Pest
- Spatie Laravel Permission

## Core Features

- Public course catalog
- Authenticated dashboard and profile management
- Role-based access with `admin`, `instructor`, and `student`
- User management
- Course CRUD with category assignment and image uploads
- Lesson CRUD with video and attachment uploads
- Enrollment flow for students
- Lesson progress tracking
- Instructor assignment per course
- Experience and profile data management
- Email verification support

## Domain Overview

Main entities in the project:

- `User`
- `Profile`
- `Experience`
- `Category`
- `Course`
- `Lesson`
- `Attachment`
- `Enrollment`
- `LessonProgress`
- `CourseInstructor`
- `Certificate`

## Getting Started

### Requirements

- PHP 8.2 or newer
- Composer
- Node.js and npm
- A database supported by Laravel

The default `.env.example` is configured for SQLite.

### Installation

```bash
composer install
php artisan key:generate
```

Create `.env` from `.env.example` before generating the key. On Windows you can use:

```powershell
Copy-Item .env.example .env
```

On macOS or Linux:

```bash
cp .env.example .env
```

If you are using SQLite, create the database file first:

```bash
type nul > database/database.sqlite
```

Then run:

```bash
php artisan migrate --seed
php artisan storage:link
npm install
```

You can also use the built-in setup script for the base install:

```bash
composer run setup
```

After that, run `php artisan db:seed` and `php artisan storage:link`.

### Run The App

For local development:

```bash
composer run dev
```

This starts:

- Laravel's local server
- the queue listener
- Laravel Pail
- the Vite dev server

If you prefer separate commands:

```bash
php artisan serve
php artisan queue:listen --tries=1 --timeout=0
npm run dev
```

## Default Seeded Account

The database seeder creates an admin user:

- Email: `test@example.com`
- Password: `password`

It also seeds:

- roles
- permissions
- 4 course categories
- 12 sample courses

## Roles And Permissions

The application uses `spatie/laravel-permission` with these roles:

- `admin`
- `instructor`
- `student`

Permissions are grouped around:

- users
- profiles
- courses
- enrollments
- instructor assignment
- dashboard access
- experiences
- lessons

## Useful Commands

```bash
composer run dev
composer run test
php artisan migrate:fresh --seed
php artisan storage:link
npm run build
```

## Testing

The project uses Pest for testing.

```bash
composer run test
```

Current tests mainly cover authentication, profile flows, and the default Laravel examples.

## File Uploads

Course images, lesson videos, and lesson attachments are stored on the `public` disk. Run `php artisan storage:link` so uploaded files are accessible from the browser.

## Main Routes

Public routes:

- `/`
- `/courses`

Authenticated areas include:

- `/dashboard`
- `/users`
- `/profile`
- `/courses/*`
- `/lessons/*`
- `/enrollments`
- `/instructors-assignment`

## Notes

- The repository currently uses Blade views under `resources/views`.
- Frontend assets are built with Vite from the Laravel app.
- Queue, cache, and session drivers are database-backed in the default environment template.
