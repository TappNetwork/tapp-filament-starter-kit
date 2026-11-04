Post-install checklist for a new project created from this starter kit.

Anyone can follow the first sections. Steps that only a Tapp team needs (Laravel Cloud organization secrets, Tapp Portal, and private packages) are marked optional and can be skipped.

Delete `.tapp/post-install-pending` when you have finished the steps you need.

Sign in at `/app/login` and register at `/app/register`. After `php artisan db:seed`, log in as `admin@dev.com` / `secret`. The admin panel (`/admin`) has no login, registration, password reset, or profile pages. Guests are sent to the app panel to authenticate. Only users with the Admin role can open `/admin`.

Set `FILAMENT_REGISTRATION=false` in `.env` to disable self-registration. Invites from the admin panel still work. New registrations must verify their email before using the app panel.

## Every project

### Nightwatch

Nightwatch is installed. Leave `NIGHTWATCH_TOKEN` empty until you want reporting. Local tests set `NIGHTWATCH_ENABLED=false`.

1. Create an application at [nightwatch.laravel.com](https://nightwatch.laravel.com).
2. Set `NIGHTWATCH_TOKEN` in `.env` (see `.env.example`).
3. Set `NIGHTWATCH_ENABLED=true` when you are ready to report.

### Queues

Locally, `composer dev` runs `queue:listen` on the database queue. No extra setup is required.

For production, keep `QUEUE_CONNECTION=database`, or point it at Redis, or use a Laravel Cloud managed queue (below). `aws/aws-sdk-php` is already installed for Cloud.

### Tests

`composer test` and `composer lint` need no Composer credentials. The tests workflow in this kit is self-contained.

## If you host on Laravel Cloud

1. Create a Laravel Cloud application connected to this repository.
2. Keep push-to-deploy on (Cloud's default). Every push to `main` deploys.
3. In the Cloud environment, choose **Add compute**, then **Managed queue**, then Flex. Pick a size, for example 256 MiB with 0 to 3 workers, then deploy.
4. Cloud sets `QUEUE_CONNECTION=cloud`. Failed jobs show up in the environment's **Queues** dashboard.

Cloud's build reads private Composer credentials from Cloud secrets in the build environment. Do not write credentials inline in a build command. This kit does not need those secrets.

## Optional for Tapp teams

Skip this section if you are not on a Tapp team.

### Laravel Cloud organization secrets

Tapp's Cloud organization already stores the credentials private packages need. New Tapp apps inherit them. Do not copy those secrets into this public starter kit.

### Tapp Portal

Sites hosted outside Laravel Cloud can install `tapp/tapp-manager` so Portal can poll failed jobs. Portal reads Laravel Cloud sites from the Cloud API, so Cloud apps can skip the package.

```bash
composer config repositories.tapp-manager vcs https://github.com/TappNetwork/Tapp-Manager
composer require tapp/tapp-manager:dev-main
```

Set `TAPP_MANAGER_TOKEN` in `.env` to the token Tapp Portal uses for the site. This is a private VCS package, so Composer needs a GitHub token locally and in CI (`GH_PAT`).

### Private packages

These are not included. Install one only when the project needs it.

HubSpot sync:

```bash
composer require tapp/laravel-hubspot tapp/hubspot-sync-filament
```

Map `first_name` to HubSpot `firstname` and `last_name` to `lastname`. These packages are private. Configure GitHub OAuth for Composer locally and in CI.

LMS: see [Filament LMS](https://github.com/TappNetwork/Filament-LMS).

Form builder: see [Filament Form Builder](https://github.com/TappNetwork/Filament-Form-Builder).

### GitHub Actions secrets

The tests workflow runs with no secrets. Auto-update and auto-fix run in a repository created from this kit. On the starter kit repository itself they skip, so they do not fail when secrets are missing.

| Secret | Used by | Required? |
|--------|---------|-----------|
| `GH_PAT` | Private VCS packages and auto-update pushes, when the default token is not enough | No |
| `COMPOSER_AUTH` | Private Composer `http-basic` or `github-oauth` JSON, if you outgrow `GH_PAT` | No |
| `CURSOR_API_KEY` | Auto-fix exception workflow. Without it the job exits successfully | No |
| `CLICKUP_API_TOKEN` | Auto-update ClickUp note. Also needs the two variables below | No |
| `FORGE_DEPLOY_WEBHOOK` | Auto-update Forge deploy after a merged dependency PR | No |

Repository variables, under **Settings → Secrets and variables → Actions → Variables**. Optional, and only for Tapp repositories that should post the auto-update note. Leave both empty on a fork or a public project. The ClickUp steps exit successfully when either variable is empty, so those repositories never need a Tapp workspace or channel id.

| Variable | Used by |
|----------|---------|
| `CLICKUP_WORKSPACE_ID` | Auto-update ClickUp note |
| `CLICKUP_CHANNEL_ID` | Auto-update ClickUp note |

## Optional: Filament Blueprint

Filament Blueprint is not part of this kit. Its license does not allow publishing the package or its agent skill files, and Composer cannot install it without a license.

If you hold a Blueprint license, add it to your own project:

```bash
composer config repositories.filament composer https://packages.filamentphp.com/composer
composer config --auth http-basic.packages.filamentphp.com "YOUR_EMAIL_ADDRESS" "YOUR_LICENSE_KEY"
composer require filament/blueprint --dev
php artisan boost:install
```

In the Boost installer, select Agent Skills, then `filament/blueprint`. That writes `planning-filament`, `reviewing-filament-plans`, and `filament-security-audit` into your agent skill folders.

This kit tells Boost to skip those three skills (`config/boost.php`, `skills.exclude`) and gitignores the folders. `boost:update` will not commit them back. If your own project should keep the skills, remove those three names from `skills.exclude` and from `.gitignore`, and do not publish the skill files.

## Done

When finished, delete the marker:

```bash
rm .tapp/post-install-pending
```
