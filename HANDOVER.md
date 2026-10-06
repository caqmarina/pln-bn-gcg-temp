# Project Handover

## Current state

- Login is available at `/login`.
- New users can register at `/auth/register-basic`; registration creates a `users` record, signs the user in, and redirects to `/dashboard`.
- `/dashboard` uses `Analytics` and renders `content.dashboard.dashboards-analytics`.

## Do next

1. Verify the full auth journey manually: register a new account, confirm the redirect to `/dashboard`, log out, and log in again with the same account.
2. Protect authenticated pages with Laravel's `auth` middleware. At present, `/dashboard` and the resource routes can be reached without logging in.
3. Remove or restore the unused template routes in `routes/web.php`. It imports controllers that do not exist, including `layouts/*`, `pages/*`, `authentications/LoginBasic`, `authentications/ForgotPasswordBasic`, cards, forms, and extended-UI controllers. These can make route listing, route caching, and requests to those routes fail. Keep only routes the application actually uses, or restore the missing controllers.
4. Run `php artisan migrate:status`, then apply outstanding migrations with `php artisan migrate` after confirming the local `gcg_db` database is the intended target. The `arahan` and `arahan_details` migrations are newly added and must be reviewed before committing.
5. Review the duplicate `employee` resource declaration in `routes/web.php` and keep one definition.
6. Add feature tests for registration, login, logout, unauthenticated dashboard access, and the dashboard redirect.
7. Before deployment, set `APP_ENV=production`, `APP_DEBUG=false`, a production `APP_URL`, and production database credentials. Do not commit `.env`.

## Useful checks

```powershell
php artisan migrate:status
php artisan test
php artisan route:list
php artisan optimize
```

Run `php artisan route:list` only after the missing template-controller routes have been removed or restored.

## Suggested next commits

```text
fix(auth): link login, registration, and dashboard routes
chore(routes): remove unused template routes
test(auth): cover registration and login flow
```
