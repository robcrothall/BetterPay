# BetterPay (v4) — Development Site

This folder (`v4`) contains the development version of the BetterPay PHP/MySQL website.

- See `INSTALL_WINDOWS_IIS.md` for local IIS + PHP setup instructions on Windows.
- Database schema: `tables.sql`.
- Local secrets: `inc/constants.php` (intentionally ignored by `.gitignore`).

Development setup:

1. Install composer (https://getcomposer.org/) and dependencies:

```bash
cd v4
composer install
```

2. Create `inc/constants.php` from `inc/constants.php.example` and update DB and SMTP settings.

3. Create the database and import schema:

```bash
mysql -u root -p < tables.sql
```

4. Ensure `uploads/` exists and is writable by the web server.


Workflow:
- Work in `v4/` locally.
- Commit changes to the repository and push to GitHub: https://github.com/robcrothall/BetterPay
- A deploy process should publish the `v4` folder to the production server under `/v4`.
