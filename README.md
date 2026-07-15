# Northwind Operations Portal

A Laravel 12 web application for the IT424 final project. It manages Northwind customers and products, displays customer orders with nested product details, and produces sales and inventory reports from the supplied MySQL Northwind database.

## Included features

- Customer DataTable with search, sorting, pagination, clickable rows, full details, edit mode, jQuery validation, Laravel validation, and AJAX updates.
- Product DataTable searchable by product name, category, supplier, and other columns, plus a validated AJAX details editor.
- Customer accordion that loads each customer's orders on demand with AJAX. Every order contains a nested product table with quantity, unit price, discount, subtotal, and calculated order total.
- Total sales report for any valid date range, including revenue after discounts, order count, average order value, and a searchable order breakdown.
- Inventory report with the exact required calculation: `Available Quantity = Units In Stock - Total Ordered`.
- Responsive Bootstrap 5 layout, jQuery interactions, DataTables, local Vite-built assets, and mobile navigation.

## Technology

- PHP 8.2+
- Laravel 12
- MariaDB/MySQL
- Bootstrap 5.3
- jQuery 3.7 and jQuery Validation
- DataTables 2 with Bootstrap 5 styling
- Vite

## Run the project

The included database startup script uses an isolated MariaDB instance on port `3307`. This prevents this project from changing any other XAMPP databases.

From PowerShell in the project directory:

```powershell
.\scripts\start-northwind.ps1
composer install
npm.cmd install
npm.cmd run build
Copy-Item .env.example .env -ErrorAction SilentlyContinue
php artisan key:generate
php artisan serve
```

Then open `http://127.0.0.1:8000`.

The supplied SQL dump is included in the project at:

```text
database\sql\northwind_2024-11-26.sql
```

To use another dump location:

```powershell
.\scripts\start-northwind.ps1 -SqlPath 'D:\path\to\northwind.sql'
```

## Database configuration

The application is configured in `.env.example` with:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=northwind
DB_USERNAME=root
DB_PASSWORD=
```

The isolated database files are stored under `storage/mariadb-data` and ignored by Git.

## Verification

With the isolated database running:

```powershell
php artisan test
npm.cmd run build
```

The feature suite verifies all main pages, AJAX listing endpoints, nested order products, exact sales totals for a known order date, date validation, and update validation without modifying the supplied records.
