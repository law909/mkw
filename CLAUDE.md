# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project overview

MKW is a custom PHP 8.1 webshop + back-office platform. The same codebase ships to many distinct deployments ("owners" / "themes"): `galad`, `darshan`,
`mkwcansas` (mindentkapni), `mugenrace`, `mugenrace2026`, `mpt`, `mptngy`, `superzoneb2b`, `ujdivat`, `b2bhungary`, `lb`, `varganyomda`, `kisszamlazo`. The
active deployment is selected by swapping `config.ini` and `setup.ini` — feature flags in those files drive large branches of behavior throughout the code. The
codebase is Hungarian-language (entity, controller, and route names are in Hungarian — e.g. `Termek` = product, `Partner` = customer, `Bizonylatfej`/
`Bizonylattetel` = document header/line, `Raktar` = warehouse, `Valutanem` = currency, `Szallitasimod` = shipping, `Fizmod` = payment). Most existing *method*
names are Hungarian too, but **new methods are written in English** — see "Naming" below.

## Switching between deployments

Each deployment has a one-line `<owner>.sh` script in the repo root that copies `<owner>config.ini` → `config.ini` and `<owner>setup.ini` → `setup.ini`. Always
run the matching script before exercising owner-specific code; `main.theme` in `config.ini` is what `mkw\store::getTheme()` returns and what the `is<Owner>()`
helpers key off of.

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
  Read via `store::getConfigValue($key)`. Parsed **without sections**, so keys are flat and dotted (`path.template`, `db.driver`, `mediatar.type.Images.ext`).
  Comments must start with `;` — a `#` line is *not* a comment to `parse_ini_file()` and can make the whole file unparseable (every request then 500s).
  - `mediatar.type.<Name>.{dir,ext,max}` — optional per-deployment override of the media library's resource types (`Services\MediatarService::getTypes()`,
    default in `DEFAULTTYPES`). Field-level: writing only `ext` keeps the default `dir`/`max`. `max` understands `50M`; `0` means the PHP upload limit decides.
- **`setup.ini`** — feature toggles per deployment (`b2b`, `multilang`, `multivaluta`, `bankpenztar`, `arsavok`, `kisszamlazo`, `pdf`, `pdfmode`, `barion`,
  `webshopnum`, `enabledwebshops`, `mediatar`, `mediatarstrictorigin`, …). Read via `store::getSetupValue($key)`.
  - `mediatar = 1` swaps the legacy CKFinder 2.3 file browser for the in-house media library (`Services/MediatarService.php`, `Controllers/mediatarController.php`,
    `js/admin/default/mediatar.js`). Missing/`0` keeps CKFinder — the switch is deliberately opt-in per deployment, and flipping it takes effect on the next
    request (clear `tpl/template_c/` so `base.tpl` recompiles). `mediatarstrictorigin = 1` turns the media library's Origin/Referer check from log-only into
    enforcing. See `docs/mediatar.md`.

`developer = 1` enables: always-regenerate proxy classes, error display, SQL logging (when `sqllog = 1`). Production should run with `developer = 0` and a real
cache (`apc` recommended).

## Request lifecycle

`index.php` is the single entry point (`.htaccess` rewrites everything to it); `bootstrap.php` wires the `EntityManager`, Gedmo, and the `Listeners/` classes.

Routes are registered in `mainroute.php`, `adminroute.php`, and `pubadminroute.php`. Handlers use the form `'<controllerFile>#<method>'` (e.g.
`'termekController#show'`). The route *name* prefix decides the mode: `admin…` → admin (auth-gated against `Dolgozo`), `pubadmin…` → public admin, anything else
→ main storefront.

When adding a route, add it under the correct `is<Owner>()` / `isB2B()` / etc. guard so it only activates for deployments that need it — see the conditionals at
the top of `mainroute.php`.

## `mkw\store` — the central service locator

Everything reaches global state through the static `mkw\store` class (`mkw/store.php`): the EntityManager, the three sessions, the router and template factory,
the logged-in user, and the `is…()` feature/theme flags.

Reach for `store::` rather than re-resolving dependencies; large swaths of the codebase assume it.

## Entities, repositories, listeners

- `Entities/` — one Doctrine entity per file with annotation mappings, plus a sibling `<Entity>Repository.php` for custom queries. Use
  `store::getEm()->getRepository(Entities\Foo::class)`.
- `Proxies/` — generated; never edit by hand. In `developer` mode they regenerate automatically; otherwise run `./generateproxies.sh` after entity changes.
- **Multilang fields**: when `isMultilang()`, translated content lives in extra columns suffixed `_l1`, `_l2`, … on the same entity (not in a separate
  translation table). The default locale is `hu_hu` with fallback enabled.
- **Entity or schema changes**: follow the `entity-change` skill — it covers proxy regeneration, DDL review before apply, `./updateschema.sh`, and the map of
  which `Listeners/` classes re-derive fields on `onFlush`/`prePersist` (your direct setter may be overwritten).

## Controller conventions

Two base classes in `mkwhelpers/`:

- **`Controller`** — base; exposes `getEm()`, `getRepo()`, view factories (`createView`, `createMainView`, `createPubAdminView`).
- **`MattableController`** — admin CRUD scaffold extending `Controller`. A subclass **must** provide these; the base calls them
  but does not declare them:
    - `loadVars($t, $forKarb = false)` — entity → template array (`getEntityFieldsArray($t)` plus relation/date extras)
    - `setFields($obj)` — request → entity. `setEntityFieldsFromRequest($obj)` maps every scalar field automatically,
      **including booleans** (an unchecked checkbox is absent from the POST and correctly becomes `false`). What it does
      *not* do: associations, and `date`/`datetime` fields (it passes the raw string, so convert to `\DateTime` yourself).
    - `getlistbody()` — the list `<tbody>` + pager JSON; this is where list filtering is written inline
    - `viewlist()` — the list page
    - `_getkarb($tplname)` — assemble the edit form (**lowercase `k`**; called by the base `getkarb()`/`viewkarb()`)

  Optional hooks that already exist (empty) in the base: `setVars($view)`, `beforeRemove($o)`, `afterSave($o, $parancs = null)`.
  Sorting comes from `getOrderArray()`, which reads the `order` request param against the repository's `setOrders()` — there is
  no `loadFilters()` hook (the only method by that name is a private helper in `bizonylatfejController`).

Per-entity template set in `tpl/admin/{theme}/`: `<entity>lista.tpl`, `<entity>lista_tbody.tpl`, `<entity>lista_tbody_tr.tpl`, `<entity>karb.tpl`,
`<entity>karbform.tpl`, plus `js/admin/default/<entity>.js` (copy `afa.js`, swap the three URLs) and 5–6 routes in `adminroute.php`
(`viewlist`, `getlistbody`, `getkarb`, `viewkarb`, optional `htmllist`, and `save` inside `if (!\mkw\store::isClosed())`).
The list's sort dropdown comes from `setOrders()` in the entity's repository — forgetting it leaves the dropdown empty.
**`MattableController` is the only admin list scaffold**; the former jqGrid-based `JQGridController` was removed in 2026-08
(see `docs/egyebtorzs-mattable-migracio.md`).

## Templates and assets

- Smarty 4 templates live in `tpl/admin/{theme}/` and `tpl/main/{theme}/`. `tpl/admin/default/` is the fallback admin theme. Compiled templates go to
  `tpl/template_c/` and caches to `tpl/admin/cache/` (both must be writable).
- Frontend JS/CSS is bundled by `Gruntfile.js`: `concat` builds per-theme `*bootstrap.js`/`*app.js` and combined CSS; `less` and `sass` compile theme
  stylesheets. Only some themes are wired into Grunt (`mkwcansas`, `mugenrace`, `mugenrace2026`) — others ship hand-edited assets.

## Naming

**In new code every identifier is English** — method, constant, property, and local variable alike:

- good: `matchTermek()`, `getTermekValtozatList()`, `downloadProductDB()`, `const BATCHKEPCSV`, `$report`, `$rows`
- bad: Hungarian verbs/nouns (`letoltes()`, `irMezok()`, `const KOTEG`, `$riport`, `$fajl`)

**Three things stay Hungarian**, and anglicizing them is always wrong:

1. **Entity names** — `Termek`, `TermekValtozat`, `TermekKep`, `Partner`, `Bizonylatfej` … keep them inside English identifiers too:
   `importTermek()`, not `importProduct()`; `getTermekValtozatList()`, not `getProductVariantList()`.
2. **Entity field / DB column names** — `cikkszam`, `vonalkod`, `nev`, `leiras`, `ertek1`, `unasid`. A variable holding one keeps the field's name (`$cikkszam`).
3. **Route names and array keys that are a contract** — report/JSON keys, request parameter names, Smarty variables. Those are not identifiers; renaming them
   silently breaks templates and JS.

This applies to **new** code. Existing Hungarian-named methods are not renamed just for the convention, and the `MattableController` hooks (`loadVars`,
`setFields`, `getlistbody`, `viewlist`, `_getkarb`) are fixed by the base class anyway.

## Comments

Comment sparingly. **If the next few lines of code say it, don't write it** — the repo is lightly commented and dense explanatory blocks stand out.

- Don't: restate the code (`// batch save` above `flush()`), repeat the method name in prose, or pad a PHPDoc with `@param` lines that add nothing to the type hint.
- Do, in **one line**: an external constraint that isn't visible in the code (`// the file expires at UNAS after an hour`), a trap
  (`// setKepurl(null) also nulls kepleiras`), or why the obvious approach was rejected (`// not getByProperties(): it interpolates into SQL`).

Test: cover the comment — is it still clear from the next 3–4 lines? Then drop it.

## Things to know that bite

- **Hungarian naming**: don't anglicize entity, column, or route identifiers when editing — match the existing convention (see "Naming" above).
- **Two SQL workflows coexist**: schema changes via `./updateschema.sh` (annotations → DDL) and ad-hoc data migrations in `runonce.php` (executed once on first
  admin request after deployment, gated by stored markers). Pick the right one.
- **Owner-specific code is everywhere**: before changing shared code, grep for `is<Owner>()` and `getTheme()` to see who else depends on the path. The same
  controller method often branches on three or four flags.
- **Sessions are split**: `main`, `admin`, and `pubadmin` are independent `session_namespace` instances — don't cross-read them.
- **Proxies + cache**: stale proxy or metadata cache after entity changes is a common confusing failure in non-developer mode — regenerate proxies and clear the
  configured cache.
