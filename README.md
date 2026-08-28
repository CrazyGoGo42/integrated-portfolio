# Portfolio

A personal portfolio website — an animated single-page frontend backed by a small PHP API that serves the gallery data from a MySQL database.

## Structure

```
.
├── frontend/                 # Everything the browser loads
│   ├── index.html            #   entry point (SPA)
│   ├── pages/                #   sub-pages loaded on demand (about, graphics, …)
│   ├── css/  js/             #   styles and scripts (GSAP, Three.js, jQuery)
│   ├── assets/               #   images, fonts, icons
│   ├── models/               #   glTF 3D models
│   └── rogue.svg             #   logo / favicon
│
├── backend/                  # PHP API (served from the same docroot)
│   ├── api.php               #   public gallery API (reads from the DB)
│   ├── admin.php             #   admin/status page
│   └── config.php            #   reads DB creds from environment
│
├── database_schema.sql       # Categories → galleries → images, plus admin auth
├── apache-spa.conf           # SPA-aware Apache vhost + API rewrites
├── Dockerfile                # php:8.1-apache; copies frontend/ + backend/ into docroot
├── docker-compose.website.yml# Website-only deploy (connects to an existing MySQL)
└── .env.example              # Copy to .env and fill in your DB connection
```

The frontend and backend live in separate folders for clarity, but the Docker image
copies both into a single Apache document root, so the frontend can call `./api.php`
directly (the vhost rewrites `/api/...` and `/admin` to the PHP files).

## How the gallery works

`frontend` fetches `./api.php`, which returns the portfolio as JSON
(`categories → galleries → images`). `api.php` reads this from MySQL and falls back to
a built-in static copy if the database is unreachable, so the site never hard-fails.

## Running it

This deployment expects an **existing** MySQL/MariaDB server (the database is not run
as part of this stack).

1. Create the schema on your database server:
   ```sh
   mysql -h <host> -P <port> -u <user> -p < database_schema.sql
   ```
2. Configure the connection:
   ```sh
   cp .env.example .env
   # edit .env with your DB host/port/name/user/password
   ```
3. Start the site:
   ```sh
   docker compose -f docker-compose.website.yml up -d --build
   ```
   The site is served on **http://localhost:3004**.

## Configuration

All configuration is via environment variables (see `.env.example`):

| Variable      | Purpose                                             |
|---------------|-----------------------------------------------------|
| `DB_HOST`     | Database host                                       |
| `DB_PORT`     | Database port                                        |
| `DB_NAME`     | Database name                                        |
| `DB_USER`     | Database user (needs access to `DB_NAME` only)       |
| `DB_PASS`     | Database password                                    |
| `ADMIN_TOKEN` | Bearer token required for admin/API write operations |
| `ENVIRONMENT` | `development` shows errors; `production` hides them   |

> `.env` holds real secrets and is gitignored — never commit it.
