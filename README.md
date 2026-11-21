# ScholarERP – School Management System

**ScholarERP** is a Laravel-based school management and enterprise resource planning system designed to support the administration and day-to-day operations of educational institutions.

The project brings together student administration, academic management, finance, attendance, examinations, staff management and reporting in one web-based application.

## Project Overview

ScholarERP is being developed to simplify school administration, improve record management and provide role-based access to important school information.

## Key Modules

- **School Administration:** Academic years, terms, classes, streams and subjects.
- **Student Management:** Student registration, admissions and student records.
- **Teacher Management:** Teacher profiles and class-teacher assignments.
- **Attendance Management:** Student attendance recording and monitoring.
- **Examinations and Results:** Examination management, marks, grading and student report cards.
- **Finance Management:** Fee categories, fee statements, payments and student wallet credits.
- **Student Documents:** Student document management with private file storage.
- **Discipline Management:** Student incidents, disciplinary actions and record tracking.
- **Timetable Management:** Academic timetable creation and management.
- **User and Role Management:** Role-based access control and permissions.
- **Audit and Backup Management:** Audit logs and database backup and restoration functionality.

## Technical Highlights

- Laravel authentication and authorization.
- Role-based access control using Spatie Laravel Permission.
- Policy-based protection of sensitive student records.
- Private storage for confidential student documents.
- Audit logging for important system activities.
- Database migrations and seeders for application setup.
- Automated feature and unit tests using Pest.
- Frontend asset compilation using Vite.

## Testing and Build Status

The current development version has passed 22 automated tests
with 42 assertions. The production frontend asset build also completes
successfully.

These results reflect the current development environment and do not
represent a production deployment certification.

## Technology Stack

| Technology | Purpose |
|---|---|
| PHP 8.2+ | Backend programming |
| Laravel 12 | Application framework |
| MySQL / MariaDB | Database |
| HTML5 and CSS3 | Frontend structure and styling |
| JavaScript | Frontend interactivity |
| Bootstrap 5 / Tailwind CSS | User interface styling |
| Vite | Frontend asset compilation |
| Spatie Laravel Permission | Role and permission management |

## Access Control

ScholarERP uses role-based access control to manage what users can view and do within the system.

The configured roles include:

- Super Admin
- Registrar
- Teacher
- Accountant
- HR/Admin

Permissions are assigned according to each role's responsibilities.

## Security Considerations

The application includes security measures such as:

- Authentication and authorization.
- Role-based permissions.
- Policy-based access control for protected records.
- Scoped access to student discipline records.
- Private storage for confidential student documents.
- Environment-based configuration for application secrets and database credentials.

**Important:** Private student documents and confidential records should never be committed to a public repository. Only non-sensitive source code and documentation should be published.

## Installation and Setup

### Requirements

- PHP 8.2 or later
- Composer
- MySQL or MariaDB
- Node.js and npm
- Git

### 1. Clone the repository

```bash
git clone https://github.com/Mayomkuol/ScholarERP.git
cd ScholarERP
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Configure the environment

Copy the example environment file:

```bash
cp .env.example .env
```

Generate an application key:

```bash
php artisan key:generate
```

Update the database settings in `.env` to match your local MySQL or MariaDB configuration.

### 4. Create the database

Create a database named `scholarerp`, or update `DB_DATABASE` in your `.env` file to use another database.

### 5. Run migrations and seeders

```bash
php artisan migrate --seed
```

The seeders configure the initial grades, roles and permissions. They do not automatically create a Super Admin login account.

### 6. Install frontend dependencies

```bash
npm install
npm run build
```

### 7. Start the development server

```bash
php artisan serve
```

Open the local application at:

```text
http://127.0.0.1:8000
```

## Running Tests

Run the application's configured test suite using:

```bash
php artisan test
```

## Project Structure

```text
ScholarERP/
├── app/
│   ├── Http/
│   ├── Models/
│   └── Policies/
├── bootstrap/
├── config/
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
├── public/
├── resources/
│   └── views/
├── routes/
├── storage/
├── tests/
├── .env.example
├── composer.json
└── README.md
```

## Project Status

ScholarERP is an ongoing software development project. Its modules and functionality are subject to continued testing, refinement and improvement.

## Author

**Mayom Kuol Madhang**

GitHub: [Mayomkuol](https://github.com/Mayomkuol)

Repository: [ScholarERP](https://github.com/Mayomkuol/ScholarERP)

## License

No project-specific open-source licence has been declared. All rights in the ScholarERP application remain reserved unless otherwise stated by the author.

