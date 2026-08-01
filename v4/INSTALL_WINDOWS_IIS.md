# Install PHP and link to IIS (Windows)

1. Install PHP (Windows):
   - Download the latest supported PHP 8.x (Non Thread Safe) ZIP from https://windows.php.net/download/
   - Extract into `C:\php8` (for example).

2. Install the Microsoft Visual C++ Redistributable if required.

3. Configure PHP for IIS (FastCGI):
   - Open `IIS Manager` -> `Handler Mappings` for the site or server.
   - Add Module Mapping:
     - Request path: `*.php`
     - Module: `FastCgiModule`
     - Executable: `C:\php8\php-cgi.exe` (adjust to your path)
     - Name: `PHP_v4_FastCGI`
   - Confirm and allow FastCGI.

4. Create a site or application in IIS for the development site:
   - Create a new site or add an application under default site pointing to the folder:
     `C:\Users\<you>\OneDrive\Documents\Dev\BetterPay\v4`
   - Use an Application Pool with **No Managed Code** and integrated pipeline.
   - Ensure the site runs under an identity that has read/write permission to the folder (grant `IIS_IUSRS` modify where uploads are stored).

5. PHP extensions:
   - Enable `pdo_mysql` in `php.ini`.

6. Database:
   - Install MySQL or MariaDB (e.g., MySQL Community Server or use WAMP/XAMPP).
   - Create a database and user as given in `inc/constants.php` or update that file to match your local DB.
   - Import `tables.sql` into the database:

```sql
mysql -u root -p betterpaydb < tables.sql
```

7. Local config:
   - Copy `inc/constants.php.example` to `inc/constants.php` and edit DB credentials and site settings.
   - `inc/constants.php` is ignored by git; never commit secrets.

8. Restart IIS and test by visiting the site URL configured in IIS.
