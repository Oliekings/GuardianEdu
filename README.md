# GuardianEdu

A comprehensive Laravel‑based school management system.  
It provides portals for super‑admin, admin, teachers, students, parents, accountants, librarians, receptionists and more, covering attendance, grades, fees, transport, library, inventory, chat, security cams, and CMS.

## Features
- Role‑based portals (Super‑Admin, Admin, Teacher, Student, Parent, Accountant, Librarian, Receptionist)
- Attendance tracking, behavioural logs, exam scheduling and grading
- Fee collection, fee groups/types, and financial reporting
- Transport fleet, route and assignment management
- Library catalogue, issue/return workflow, inventory management
- Real‑time chat and camera feeds (Reverb/WebSockets)
- CMS for school announcements and enquiries
- Fully API‑driven with Inertia.js + Vue 3 front‑end

## Installation
```bash
# Clone the repository
git clone https://github.com/Oliekings/GuardianEdu.git
cd GuardianEdu

# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Set up environment file
cp .env.example .env
php artisan key:generate

# Run migrations (SQLite used by default; adjust DB settings in .env if needed)
php artisan migrate

# Build assets
npm run build

# Serve the application
php artisan serve
```

## Contributing
Contributions are welcome! Please read the [CONTRIBUTING guidelines](CONTRIBUTING.md) (to be added) and follow the standard Laravel contribution process.

## License
This project is open‑source software licensed under the MIT license.

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

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
