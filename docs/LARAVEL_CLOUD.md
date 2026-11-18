# Deploy the starter kit on Laravel Cloud

Deploy an app created from this kit to a `laravel.cloud` URL.

These steps come from [Laravel Cloud's documentation](https://cloud.laravel.com/docs/intro) and from this repository. Local checks were the migrations, the test suite on Postgres 16, `php artisan optimize`, and the database seeder. The suite was not run on Postgres 18. A new Serverless Postgres cluster defaults to Postgres 18. No Cloud application was created to prove this page. The dashboard clicks and a Cloud deploy log were not observed.

Local Herd installs keep using SQLite. Follow the [README](../README.md) for that setup.

## Create the repository

Cloud's template list only includes the official Laravel kits: Laravel, React, Vue, Svelte, and Livewire. This kit is not in that list.

Create the Git repository first, with the commands in the [README](../README.md). Push it to GitHub, GitLab, or Bitbucket. Cloud deploys from that repository.

## Create the application

The **Use a template** control installs an official Laravel kit. Select the repository that already contains this app.

1. Open [Laravel Cloud](https://cloud.laravel.com) and select **+ New application**.
2. Connect GitHub, GitLab, or Bitbucket if Cloud asks you to.
3. Select the repository.
4. Name the application.
5. Choose a region. The application and its database must use the same region. US East (Ohio), `us-east-2`, is one region Cloud offers. Cloud's pricing examples use that region.
6. Create the application.

Cloud creates a default environment. **Push to deploy** is on by default. A push to the environment's branch starts a deploy. To deploy only when you ask, turn **Push to deploy** off under **Settings**, then **Deployments**.

## Confirm PHP 8.5

The kit requires PHP `^8.5`. New Cloud environments default to PHP 8.5.

Open the environment's **General Settings**. Under **Runtime**, confirm the version is PHP 8.5. If another version is selected, select PHP 8.5. The change applies on the next deploy.

## Set the application key

Cloud's quickstart tells you to set `APP_KEY` yourself. Generate a key locally, then paste it into the environment variables:

```bash
php artisan key:generate --show
```

Set `APP_KEY` before the first deploy. The build runs `php artisan optimize`, and that command caches configuration. The key must be present when the build runs. Do not commit the key.

Leave `APP_DEBUG` unset. The kit defaults it to false. Leave `ADMIN_PASSWORD` unset when the seeder should generate a password. Set `ADMIN_EMAIL` and `ADMIN_PASSWORD` in the environment variables before the first deploy when you want to choose them. Do not commit those values.

## Attach a database

Cloud resets the environment filesystem on every deploy. SQLite is not supported there. Attach a database before the first deploy.

1. Open the environment's infrastructure canvas.
2. Choose **Add database**.
3. Create a **Laravel Serverless Postgres** database. **Laravel MySQL** is the other database Cloud offers here. This kit's schema and tests were run on Postgres. They were not run on MySQL.
4. Put the database in the same region as the application.
5. For a test environment, choose the **Dev** preset. Cloud's docs say that preset keeps 1 day of backup history. New Serverless Postgres clusters use Postgres 18 unless you select 17.
6. Attach the database to the environment.

When you attach the database, Cloud injects the connection variables. The Serverless Postgres docs name `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_USERNAME`, `DB_PASSWORD`, and `DB_DATABASE`. Leave the injected values in place. Redeploy after you attach the database so the new variables take effect.

The kit defaults sessions, cache, and queues to the `database` driver. A new app can keep cache and sessions on that database.

## Set the build and deploy commands

The PHP build image includes Node.js and npm. It does not include Bun. This kit installs frontend packages with Bun (`bun.lock`) and builds them with `vite-plus` (`vp build`). There is no `package-lock.json`. An `npm ci` build cannot install those packages.

Follow Cloud's [Bun build commands](https://cloud.laravel.com/docs/knowledge-base/using-yarn-bun-pnpm) for a Laravel app. Remove the default npm commands. In the environment's **Deployments** settings, set the build command to:

```bash
composer install --no-dev --no-interaction
npm install -g bun
bun install
bun run build
php artisan optimize
```

`php artisan optimize` completed locally against this kit. Run it in the build. Cloud does not keep filesystem changes made by the deploy command.

For the first deploy, set the deploy command to:

```bash
php artisan migrate --force --seed --no-interaction
```

Keep Composer tokens, GitHub tokens, and other secrets out of the build command. This public kit does not need them. [`POST_INSTALL.md`](POST_INSTALL.md) covers private packages and Cloud organization secrets.

Build and deploy commands time out after 15 minutes.

## Choose compute

For a small or idle app, open the environment's **App** cluster. Use a Flex size, such as Flex 512 MiB, with no autoscaling.

Enable **Scale to zero** and set the sleep timeout. Only Flex sizes can scale to zero. Deploy after you save the cluster.

A sleeping environment wakes on the next HTTP request. New Flex sizes wake in under 500 milliseconds. Legacy Flex sizes can take 5 to 20 seconds. Cloud also wakes the environment for Laravel scheduled tasks. This kit does not register any scheduled tasks.

Jobs on the `database` queue do not wake a sleeping environment. A job that is still running when the sleep timeout ends can be interrupted. When jobs must run while the app sleeps, add a managed queue. The steps are in [`POST_INSTALL.md`](POST_INSTALL.md).

## Deploy and copy the admin password

1. Deploy the environment.
2. Wait until the deployment finishes.
3. Open the deployment log.
4. Find the seeder output.

On a new database the seeder prints `Local admin account`, then a line that starts with `Email:`, then a line that starts with `Password:`. The default email is `admin@dev.com` unless you set `ADMIN_EMAIL`. Copy the full value after `Password:`. The generated password can include punctuation.

A later seed prints `Local admin already exists.` and the email. It does not print the password. It leaves the existing password in place.

Those lines are what `php artisan db:seed` prints locally. This page did not confirm the same text inside a Cloud deployment log.

After that first seed succeeds, set the deploy command to:

```bash
php artisan migrate --force
```

Change it before the next deploy. Use that migrate command for later deploys.

## Open the app

After the first successful deployment, confirm `APP_URL` in the environment variables. Cloud's docs name `APP_URL` as a variable it can inject. The kit uses `http://localhost` when `APP_URL` is empty. If the value is empty, or it is still `http://localhost`, set it to the environment URL and deploy again.

Copy the `laravel.cloud` URL from the environment page. Cloud assigns that URL after the first successful deployment.

- The app panel is at `/app/login`.
- Registration is at `/app/register`.
- The admin panel is at `/admin`. It requires the Admin role. Guests sign in on the app panel first.

## Keep uploaded files

The environment disk is wiped on each deploy. Each replica has its own disk. Media Library, which this kit installs, stores files on the `public` disk unless `MEDIA_DISK` is set. That default disk is local.

Do both of the following before you store files that must survive a deploy:

1. Add the S3 adapter. It is not a dependency of this kit yet.

    ```bash
    composer require league/flysystem-aws-s3-v3 "^3.0" --with-all-dependencies
    ```

2. On the infrastructure canvas, choose **Add bucket** and create a **Laravel Object Storage** bucket. For files served in the browser, create the bucket as public and set the disk name to `public`. Attach the bucket, then deploy again.

Cloud configures that disk for Laravel. A public bucket exposes every object through its public URL. Laravel can issue a temporary URL for an object in a private bucket. Cloudflare R2 backs these buckets. R2 does not mix public and private files in one bucket. Do not set Flysystem `visibility` to `public`.

This page did not attach a bucket or upload a file.

## After the first deploy

Read [`POST_INSTALL.md`](POST_INSTALL.md) for Nightwatch, `FILAMENT_REGISTRATION`, managed queues, and the optional Tapp steps.

If the Cloud application was only a test, delete the application when you are done. Deleting the application leaves the database in place. Delete the database from the organization's **Resources** page when you do not need it. A sleeping app can still incur storage charges. It can also incur compute charges when it wakes.

## What it costs

Prices change. They depend on the region and the plan. Read the current [plans and pricing](https://cloud.laravel.com/docs/pricing) before you budget from this section.

Cloud's pricing page includes a US East (Ohio) side-project example. In that example, Flex 512 MiB compute is $0.00893 per hour while the instance is awake. Serverless Postgres compute is $0.106 per hour multiplied by the number of compute units. Postgres storage is $0.50 per GB-month. The example total is about $0.40 for a few hours of use. Those figures are Cloud's published example. They are not a bill measured from this kit. The plan fee is separate. Compute is billed for at most 28 days in a billing period.
