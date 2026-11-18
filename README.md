# Tapp Filament Starter Kit

An opinionated [Laravel](https://laravel.com) + [Filament](https://filamentphp.com) starter kit built by [Tapp Network](https://tappnetwork.com), with a strong focus on code quality and AI-assisted development.

> Based on the excellent work by **[Nuno Maduro](https://github.com/nunomaduro/laravel-starter-kit)** — we've extended it with our own conventions, Filament v5, and tooling to accelerate AI-driven Laravel development.

## What's Included

- **[Filament v5](https://filamentphp.com)** — `app` panel for login, registration, password reset, email verification, and profile; `admin` panel for Users (roles, invite, impersonate)
- **Tapp packages** — invite, footer, maillog, auth log, auditing (Spatie permission + media library included)
- **MCP.** Admins create Sanctum tokens on the admin panel and connect an agent to a read-only server at `/mcp/app`
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

Or use Composer directly. `post-create-project-cmd` generates the app key, creates the SQLite file, migrates, and seeds. `composer setup` installs Bun dependencies, builds assets, migrates, and seeds. The first seed prints the local admin email and password in the terminal.

```bash
composer create-project tapp/tapp-filament-starter-kit my-app
cd my-app
composer setup
composer dev
```

Sign in at `/app/login` (register at `/app/register`). The default admin email is `admin@dev.com`. Set `ADMIN_EMAIL` and `ADMIN_PASSWORD` in `.env` before seeding to choose them. Leave `ADMIN_PASSWORD` empty to generate a password. That password is printed once, in the seed output, when the admin user is created. The admin panel is at `/admin` and requires the Admin role. Set `FILAMENT_REGISTRATION=false` to disable self-registration. To set a new password, see [`docs/POST_INSTALL.md`](docs/POST_INSTALL.md).

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

## Connect an AI agent

The admin panel includes an API tokens page. Only a user with the Admin role can open it. The admin panel already requires that role, and the page checks it again. Sign in at `/app/login`, open `/admin`, then open the user menu and choose API tokens.

Create a token, name it, and check Application Summary Tool. Copy the token when the page shows it. That value is shown once. The same screen gives you a setup prompt and a JSON config for the App server. The URL is this app's `/mcp/app` route, using your `APP_URL`.

Paste the prompt into Claude Code, Cursor, or Grok, and give the agent the token through its own secure input or MCP config. Do not paste the token into the chat. The JSON config is the HTTP MCP shape those clients already use. The token is sent as `Authorization: Bearer <token>`.

The example tool returns the application name and the number of users. It does not change data. A token can call only the tools you checked. A call without that ability is refused, and so is a signed-in session that has no token.

This kit does not install a tool-call log. Model changes are already on the admin auditing page. To record each MCP tool call, install `spatie/laravel-activitylog`. The token package writes that log when the package is installed.

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
