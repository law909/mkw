# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project overview

MKW is a custom PHP 8.1 webshop + back-office platform. The same codebase ships to many distinct deployments ("owners" / "themes"): `galad`, `darshan`,
`mkwcansas` (mindentkapni), `mugenrace`, `mugenrace2026`, `mpt`, `mptngy`, `superzoneb2b`, `ujdivat`, `b2bhungary`, `lb`, `varganyomda`, `kisszamlazo`. The
active deployment is selected by swapping `config.ini` and `setup.ini` — feature flags in those files drive large branches of behavior throughout the code. The
codebase is Hungarian-language (entity, controller, method, and route names are in Hungarian — e.g. `Termek` = product, `Partner` = customer, `Bizonylatfej`/
`Bizonylattetel` = document header/line, `Raktar` = warehouse, `Valutanem` = currency, `Szallitasimod` = shipping, `Fizmod` = payment).

## Switching between deployments

Each deployment has a one-line shell script in the repo root (`galad.sh`, `darshan.sh`, `mugenrace2026.sh`, `mkw.sh`, `mpt.sh`, `mptngy.sh`, `superzoneb2b.sh`,
`ujdivat.sh`, `b2bhungary.sh`, `lb.sh`, `mugenrace.sh`) that copies `<owner>config.ini` → `config.ini` and `<owner>setup.ini` → `setup.ini`. Always run the
matching script before exercising owner-specific code; `main.theme` in `config.ini` is what `mkw\store::getTheme()` returns and what the `is<Owner>()` helpers
key off of.

## Common commands

```bash
composer install                 # PHP deps (platform pinned to php 8.1)
./<owner>.sh                     # activate a deployment (see list above)
./generateproxies.sh             # regen Doctrine proxies (also auto in developer mode)
./updateschema.sh                # apply entity changes to DB (orm:schema-tool:update --force)
./updatesql.sh                   # dump pending schema SQL to update.sql (no DB change)
php vendor/bin/doctrine <cmd>    # any Doctrine ORM CLI command (cli-config.php wires the EM)
npx grunt                        # bundle JS (concat) + CSS (less/sass) per theme
docker compose up                # local apache + php-fpm 8.3 stack, exposes mkw.local via Traefik
```

There is no formal test framework. Per `.junie/AGENTS.md`: do not create tests unless explicitly asked.

## Configuration

Two files drive behavior, both `parse_ini_file`'d in `bootstrap.php` and stored on `mkw\store`:

- **`config.ini`** — DB credentials, mail/SMTP, paths (templates, images), `developer`, `sqllog`, `cache` (`apc` | `file` | empty = ArrayAdapter), `main.theme`.
  Read via `store::getConfigValue($key)`.
- **`setup.ini`** — feature toggles per deployment (`b2b`, `multilang`, `multivaluta`, `bankpenztar`, `arsavok`, `kisszamlazo`, `pdf`, `pdfmode`, `barion`,
  `webshopnum`, `enabledwebshops`, …). Read via `store::getSetupValue($key)`.

`developer = 1` enables: always-regenerate proxy classes, error display, SQL logging (when `sqllog = 1`). Production should run with `developer = 0` and a real
cache (`apc` recommended).

## Request lifecycle

`index.php` is the single entry point (`.htaccess` rewrites everything to it). The flow:

1. `bootstrap.php` builds the Doctrine `EntityManager`, registers Gedmo listeners (Sluggable / Timestampable / Blameable, and Translatable when
   `isMultilang()`), and registers the project's own listeners from `Listeners/` (see below). Custom DQL functions `YEAR`, `NOW`, `IF`, `RAND`, `CURDATE` are
   registered here.
2. `mainroute.php`, `adminroute.php`, and `pubadminroute.php` register AltoRouter routes. Route handlers use the form `'<controllerFile>#<method>'` (e.g.
   `'termekController#show'`). The route *name* prefix decides the mode: `admin…` → admin (auth-gated against `Dolgozo`), `pubadmin…` → public admin, anything
   else → main storefront.
3. `callTheController()` in `index.php` resolves `<name>Controller` to `\Controllers\<name>Controller`, instantiates it with a `mkwhelpers\ParameterHandler`,
   and invokes the method.

When adding a route, add it under the correct `is<Owner>()` / `isB2B()` / etc. guard so it only activates for deployments that need it — see the conditionals at
the top of `mainroute.php`.

## `mkw\store` — the central service locator

Everything reaches global state through the static `mkw\store` class (`mkw/store.php`):

- EntityManager: `store::getEm()`
- Sessions: `getMainSession()`, `getAdminSession()`, `getPubAdminSession()` (each is a `session_namespace`)
- Router, template factory, sanitizer, translation listener
- Feature flags: `isB2B()`, `isMultilang()`, `isMultiValuta()`, `isBankpenztar()`, `isArsavok()`, `isFoglalas()`, `isOsztottFizmod()`, `mustLogin()`, …
- Theme flags: `isDarshan()`, `isMugenrace()`, `isMugenrace2026()`, `isMPT()`, `isMPTNGY()`, `isMindentkapni()`, `isSuperzoneB2B()`, `isGalad()`,
  `isKisszamlazo()`, `isVarganyomda()`
- Logged-in user accessors and the `BlameableListener` user setter

Reach for `store::` rather than re-resolving dependencies; large swaths of the codebase assume it.

## Entities, repositories, listeners

- `Entities/` — one Doctrine entity per file with annotation mappings, plus a sibling `<Entity>Repository.php` for custom queries. Use
  `store::getEm()->getRepository(Entities\Foo::class)`.
- `Proxies/` — generated; never edit by hand. In `developer` mode they regenerate automatically; otherwise run `./generateproxies.sh` after entity changes.
- After any entity field change run `./updateschema.sh` (or inspect first with `./updatesql.sh`).
- **Multilang fields**: when `isMultilang()`, translated content lives in extra columns suffixed `_l1`, `_l2`, … on the same entity (not in a separate
  translation table). The default locale is `hu_hu` with fallback enabled.
- **Lifecycle listeners** (`Listeners/`, registered in `bootstrap.php`): `BizonylatfejListener`, `BankbizonylatfejListener`, `PenztarbizonylatfejListener`,
  `BizonylattetelListener`, `KuponListener`, `RendezvenyListener`, `JogareszvetelListener`, `PartnerListener`, `MPTNGYSzakmaianyagListener`, `ArsavListener`.
  When editing the corresponding entities, check the listener — it often mutates state on `onFlush`/`prePersist` and your direct setter may be re-derived.

## Controller conventions

Two base classes in `mkwhelpers/`:

- **`Controller`** — base; exposes `getEm()`, `getRepo()`, view factories (`createView`, `createMainView`, `createPubAdminView`).
- **`MattableController`** — admin CRUD scaffold extending `Controller`. The standard hooks to override (per `.junie/AGENTS.md`):
    - `loadFilters($filter)` — list-view filters
    - `loadVars($t, $forKarb)` — template variables for the edit form
    - `setFields($obj)` — write form fields back onto the entity
    - `afterSave($o, $parancs)` — post-save side effects
    - `getlistbody()` — list rows
    - `_getKarb($tplname)` — assemble the edit form

Per-entity template set in `tpl/admin/{theme}/`: `<entity>lista.tpl`, `<entity>lista_tbody.tpl`, `<entity>lista_tbody_tr.tpl`, `<entity>karb.tpl`,
`<entity>karbform.tpl`. There is also `mkwhelpers/JQGridController.php` for jqGrid-based admin lists.

## Templates and assets

- Smarty 4 templates live in `tpl/admin/{theme}/` and `tpl/main/{theme}/`. `tpl/admin/default/` is the fallback admin theme. Compiled templates go to
  `tpl/template_c/` and caches to `tpl/admin/cache/` (both must be writable).
- Frontend JS/CSS is bundled by `Gruntfile.js`: `concat` builds per-theme `*bootstrap.js`/`*app.js` and combined CSS; `less` and `sass` compile theme
  stylesheets. Only some themes are wired into Grunt (`mkwcansas`, `mugenrace`, `mugenrace2026`) — others ship hand-edited assets.

## Things to know that bite

- **Hungarian naming**: don't try to anglicize identifiers when editing — match the existing convention. Use the glossary above when reading unfamiliar names.
- **Two SQL workflows coexist**: schema changes via `./updateschema.sh` (annotations → DDL) and ad-hoc data migrations in `runonce.php` (executed once on first
  admin request after deployment, gated by stored markers). Pick the right one.
- **Owner-specific code is everywhere**: before changing shared code, grep for `is<Owner>()` and `getTheme()` to see who else depends on the path. The same
  controller method often branches on three or four flags.
- **Sessions are split**: `main`, `admin`, and `pubadmin` are independent `session_namespace` instances — don't cross-read them.
- **Proxies + cache**: stale proxy or metadata cache after entity changes is a common confusing failure in non-developer mode — regenerate proxies and clear the
  configured cache.
