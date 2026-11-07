# AJWorldTech portfolio backend

PHP 8.1+ and MySQL 8/MariaDB backend scaffold for the AJWorldTech portfolio.

## Included
- PDO database connection using hosting environment variables
- Admin authentication with password hashing and session ID regeneration
- CSRF protection on admin forms and contact submissions
- CMS records for services, products, projects and articles
- Contact enquiry endpoint and admin inbox
- Responsive admin dashboard

## Hosting requirements
GitHub Pages only serves static files. This PHP application must be deployed to PHP/MySQL hosting (for example, cPanel). Keep GitHub as the source repository.

Required PHP extensions: PDO, pdo_mysql, mbstring, openssl.
Recommended PHP: 8.1 or newer.

## Installation
1. Create a MySQL database and database user in your hosting control panel. Grant the user privileges on that database.
2. Import `database/schema.sql` using phpMyAdmin.
3. Upload the repository to a PHP-enabled hosting account. Point the domain/document root to the project directory (or preferably configure the public document root separately before production).
4. Configure these environment variables in the hosting control panel:
   - `DB_HOST`
   - `DB_NAME`
   - `DB_USER`
   - `DB_PASS`
   - `APP_URL` (for example, https://ajworldtech.com)
   - `APP_ENV=production`
5. Create the first administrator from the hosting terminal/SSH:
   `php scripts/create-admin.php "AJWorldTech Admin" admin@ajworldtech.com "A-STRONG-UNIQUE-PASSWORD"`
6. Visit `/admin/login.php`.

Do not commit database credentials or administrator passwords to GitHub. Delete or disable the setup script after creating the first admin if you do not need it again.

## Contact form integration
The endpoint is `/api/contact.php`. Submit a POST request with `name`, `email`, `phone`, `subject`, `message`, and the session CSRF token in `_csrf`. A hidden `website` honeypot field is supported. The current static GitHub Pages frontend is not yet wired to this endpoint; that integration is a separate deployment step once the PHP host is available.

## Important
This is the initial backend foundation, not a finished live deployment. It does not include SMTP notifications, media upload management, public CMS rendering, password reset, audit logs, or role-based permission screens yet. Configure HTTPS before using admin credentials.
