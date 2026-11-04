# Tapp Filament Starter Kit

An opinionated [Laravel](https://laravel.com) + [Filament](https://filamentphp.com) starter kit built by [Tapp Network](https://tappnetwork.com), with a strong focus on code quality and AI-assisted development.

> Based on the excellent work by **[Nuno Maduro](https://github.com/nunomaduro/laravel-starter-kit)** — we've extended it with our own conventions, Filament v5, and tooling to accelerate AI-driven Laravel development.

## What's Included

- **[Filament v5](https://filamentphp.com)** — `app` panel for login, registration, password reset, email verification, and profile; `admin` panel for Users (roles, invite, impersonate)
- **Tapp packages** — invite, footer, maillog, auth log, auditing (Spatie permission + media library included)
- **Nightwatch** — exception tracking and monitoring (configure after install). Queues run on a Laravel Cloud managed queue in production.
- **[nunomaduro/essentials](https://github.com/nunomaduro/essentials)** — strict models, auto eager loading, immutable dates, and better Laravel defaults
- **[Laravel Boost](https://github.com/laravel/boost)** — MCP server with tools for building Laravel apps with AI agents
- **Strict PHP** — 100% type coverage, PHPStan at max strictness, Rector for automated refactoring
- **[Pest v5](https://pestphp.com)** — modern PHP testing with 100% code coverage enforced
- **[Tailwind CSS v4](https://tailwindcss.com)** + Bun for fast frontend builds

## Getting Started

> Requires [PHP 8.5+](https://php.net/releases/), [Bun](https://bun.sh), and a code coverage driver like [Xdebug](https://xdebug.org/docs/install).

Create an app with the Laravel installer:

```bash
laravel new my-app --using=tapp/tapp-filament-starter-kit
cd my-app
composer dev
```

In [Laravel Herd](https://herd.laravel.com), create a new site, choose the starter kit **Custom...**, and enter `tapp/tapp-filament-starter-kit`.

Or use Composer directly. `post-create-project-cmd` generates the app key, creates the SQLite file, and migrates. `composer setup` installs Bun dependencies and builds assets.

```bash
composer create-project tapp/tapp-filament-starter-kit my-app
cd my-app
composer setup
composer dev
```

Sign in at `/app/login` (register at `/app/register`). After `php artisan db:seed`, the local admin is `admin@dev.com` / `secret`. The admin panel is at `/admin` and requires the Admin role. Set `FILAMENT_REGISTRATION=false` to disable self-registration.

### Optional: Browser Testing

```bash
bun add playwright
bunx playwright install
```

### Verify Installation

```bash
composer test
```

All quality checks should pass with 100% test and type coverage.

## Post-install

After install, see [`docs/POST_INSTALL.md`](docs/POST_INSTALL.md) for Nightwatch, queues, and Laravel Cloud. Tapp Portal, private packages, and organization secrets are marked optional for Tapp teams. Filament Blueprint is not included. Licensed developers can add it with the steps in that doc.

## Tooling

| Command | Description |
|---|---|
| `composer dev` | Start server, queue worker, log monitor, and Vite concurrently |
| `composer lint` | Run Rector, Pint, and JS formatter |
| `composer test` | Full suite — type coverage, unit tests, lint, static analysis |
| `composer test:unit` | Pest with 100% code coverage |
| `composer test:types` | PHPStan at level max |
| `composer test:type-coverage` | Pest type coverage (min 100%) |
| `composer test:lint` | Dry-run lint for CI |
| `composer update:requirements` | Bump all PHP and Bun dependencies |

## License

MIT — built on top of work by **[Nuno Maduro](https://x.com/enunomaduro)**, extended by **[Tapp Network](https://tappnetwork.com)**.
