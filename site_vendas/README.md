# XPC — Application setup

[Project overview and preview](../README.md) · This directory contains the PHP store. For the feature walkthrough, see the project README above.

## Start the application

Requires PHP 8.0+ and Composer. From this directory:

```bash
composer install
php -S localhost:8000
```

Open `http://localhost:8000/`. Keep `storage/` writable by PHP. The built-in server is for local development.

## Email

Create a local `.env` in this directory; `inc/config.php` reads it directly. Use your SMTP provider's values:

```dotenv
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your-smtp-user
MAIL_PASSWORD=your-smtp-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=store@example.com
MAIL_FROM_NAME=XPC
```

PHPMailer is installed by Composer. Without its autoloader or working SMTP settings, email delivery fails. Keep `.env` and its credentials out of version control.

## Accounts and data

- `inc/config.php` defines `ADMIN_EMAIL` and `ADMIN_DEFAULT_PASSWORD` for initial administrator creation. The old `admin@admin.com` README example does not match the current configuration.
- Existing users are loaded from `storage/database.json`. Editing the seed constants does not change their credentials.
- `inc/db.php` implements the JSON data store; `DB_PATH` mentioning SQLite in the configuration is not used by this data layer.
- Back up `storage/database.json` before testing changes. Do not remove it to reset a password: it also contains the store's other data.
- `admin/` holds the administration pages; `inc/` contains shared logic and layout; `misc/` contains styles, scripts and images.
- Checkout creates an internal order and updates stock; no payment is processed.
