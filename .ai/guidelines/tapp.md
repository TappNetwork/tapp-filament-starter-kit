# Tapp conventions

This kit is Laravel 13 and Filament 5. `tests/Unit/Arch` enforces the rules below. Match those tests.

## Where code goes

- Put one operation in `app/Actions` as a `final readonly` class. Name it with a verb and the `Action` suffix, and give it `handle()`.
- Put string-backed enums in `app/Enums`. Put shared enum behavior in `app/Enums/Concerns` traits.
- Put framework-agnostic work in `app/Services`. Inject dependencies in the constructor. Do not type-hint HTTP requests, controllers, or form requests. Do not call facades or the `auth`, `session`, `request`, `app`, or `resolve` helpers.
- Admin panel code lives under `app/Filament`. Resources go in `app/Filament/Resources`, pages in `app/Filament/Pages`, widgets in `app/Filament/Widgets`. `AdminPanelProvider` discovers those paths.
- App panel code lives under `app/Filament/App`. Resources go in `app/Filament/App/Resources`, pages in `app/Filament/App/Pages`, widgets in `app/Filament/App/Widgets`. Auth screens for that panel live in `app/Filament/App/Pages/Auth`. `AppPanelProvider` discovers those paths.
- Put shared Filament traits in `app/Filament/Concerns`.
- Remove `.gitkeep` when you add the first file to a directory.
- Keep these directories. Do not add a new top-level directory under `app/` without approval, and do not add a dependency without approval.

## PHP

- Use PHP 8.5 features.
- Declare `strict_types=1` in every PHP file.
- Make every class in `App` `final`.
- Use strict comparisons.
- Do not call `dd`, `ddd`, `dump`, `var_dump`, `die`, `exit`, or `ray`.
- Call `env()` only from `config/`.
- Do not use the `DB` facade. Query through Eloquent. Keep query builder usage in models, services, actions, jobs, console commands, Filament code, and `database/`.
- Document array shapes in PHPDoc. PHPStan runs at level `max` (`phpstan.neon`).
- Follow `pint.json`, including its strict types and `final` class rules.

## HTTP, validation, and models

- Do not add a base or abstract controller. Leave `App\Http\Controllers` unused. Filament pages, resources, and actions own HTTP.
- Validate a Filament form in its schema class. The user form is `App\Filament\Resources\Users\Schemas\UserForm`.
- A form request extends `FormRequest`, ends in `Request`, and implements `rules()` and `authorize()`. Name it for the write: `Create`, `Update`, or `Delete`.
- Do not add `$fillable` on a model. Define casts in `casts()`. Add a `{Model}Factory` and the `HasFactory` trait.
- Models do not use HTTP, jobs, services, or actions.
- When you add a migration, do not write `down()`. Leave published package migrations unchanged.

## Filament class names

- Resource classes end in `Resource`.
- Schema classes under `app/Filament/Resources/*/Schemas` end in `Form`.
- Table classes under `app/Filament/Resources/*/Tables` end in `Table`.
- Panel providers live in `App\Providers\Filament`, end in `Provider`, and extend `PanelProvider`.
- Widget classes extend `Widget`.
- Do not import `Filament\Tables\Actions`, `Filament\Forms\Actions`, `Filament\Forms\Get`, or `Filament\Forms\Set`.

## Other classes the arch tests lock

- Jobs implement `ShouldQueue`, end in `Job`, and define `handle()` and `failed()`.
- Notifications and mailables implement `ShouldQueue`.
- Observers end in `Observer`. Policies end in `Policy`. Validation rule classes end in `Rule`.
- `App\Contracts` holds interfaces. `App\ValueObjects` classes are `final readonly` and use nothing else.
- Every `Concerns` namespace is traits only.
- The strict arch preset ignores `App\Filament`, `App\Providers\Filament`, and `App\Models\User`. Every other `App` class stays concrete, with public or private methods.

## Tests and the gate

- Write Pest tests.
- Put Filament HTTP coverage in `tests/Feature/Filament`.
- Put model tests in `tests/Unit/Models`, action tests in `tests/Unit/Actions`, service tests in `tests/Unit/Services`, and enum tests in `tests/Unit/Enum`.
- Put browser tests in `tests/Browser`. Leave architecture tests in `tests/Unit/Arch`.
- Keep 100% type coverage and 100% line coverage.
- Run `composer lint`, then `composer test`, before a pull request. `composer test` runs type coverage, line coverage, Pint, Rector, and PHPStan.
- Style custom CSS with Tailwind CSS v4 in `resources/css/app.css`.
