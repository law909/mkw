---
name: entity-change
description: Apply Doctrine entity changes safely in this repo — regenerate proxies, review the schema diff, apply DDL, and verify no listener overrides the new field. Use whenever a file under Entities/ is being added or modified.
---

# entity-change

Canonical workflow for adding/changing fields on a Doctrine entity in `Entities/`. Skipping a step here is the #1 source of "works in dev, breaks on staging" failures in this codebase (per `CLAUDE.md`).

## When to use

Trigger this whenever any of the following happen:
- A new file is created under `Entities/`
- An existing `Entities/*.php` file has fields, relations, or annotations changed
- A `@Gedmo\Translatable` or other multilang-affecting annotation is added/removed
- A new repository method requires a column that doesn't exist yet

Do **not** trigger for changes to `Entities/*Repository.php` alone (those don't change schema).

## Pre-flight checks

1. **Which deployment is active?** Run `grep '^main.theme' config.ini`. Schema changes apply to *whatever DB `config.ini` points at right now* — make sure that's the deployment you intended.
2. **Is `developer = 1`?** Check `config.ini`. If yes, proxies regenerate automatically and step 2 below is optional (but cheap). If no, step 2 is mandatory or the next request will hit a stale proxy.
3. **Is there a listener on this entity?** See the listener map below — if yes, read the listener *before* relying on a new setter, because `onFlush` / `prePersist` may overwrite it.

## The four steps

Run in order. Stop and surface any error before moving on.

### 1. Make the entity edit
Edit the `Entities/<Name>.php` file. Add the field with the correct `@ORM\Column` annotation, plus matching getter/setter. Match the existing style in the file (Hungarian identifiers, snake-cased column names when the property is camelCase via `name=` if the existing fields use that convention).

If `isMultilang()` is in play for this deployment, translated text fields live in **extra columns** on the same entity suffixed `_l1`, `_l2`, … — they are not in a separate translation table. Add the suffix columns manually if the field should be translatable; do not assume Gedmo creates them.

### 2. Regenerate proxies
```bash
./generateproxies.sh
```
This runs `php vendor/bin/doctrine orm:generate-proxies`. Required in non-developer mode; harmless in developer mode.

### 3. Review the pending DDL before applying it
```bash
./updatesql.sh
cat update.sql
```
This dumps `orm:schema-tool:update --dump-sql` to `update.sql` **without** touching the DB. Read it. Look for:
- `DROP COLUMN` / `DROP TABLE` — likely a mistake; entity probably has a typo or missing annotation
- `DROP INDEX … ADD INDEX …` cycles — usually a no-op annotation drift, fine
- Column type changes on populated tables — may need a data migration in `runonce.php` first

If anything looks destructive, **stop and confirm with the user** before step 4.

### 4. Apply the schema
```bash
./updateschema.sh
```
This runs `orm:schema-tool:update --force` against the DB in the active `config.ini`. There is no rollback.

## Listener overrides — check before adding setters

If you are editing one of these entities, the listed listener runs on `prePersist` / `onFlush` and may re-derive fields you just set. Read the listener first:

| Entity                         | Listener (in `Listeners/`)         | Triggers                  |
|--------------------------------|------------------------------------|---------------------------|
| `Bizonylatfej`                 | `BizonylatfejListener`             | `onFlush`, `prePersist`   |
| `Bankbizonylatfej`             | `BankbizonylatfejListener`         | `onFlush`, `prePersist`   |
| `Penztarbizonylatfej`          | `PenztarbizonylatfejListener`      | `onFlush`, `prePersist`   |
| `Bizonylattetel`               | `BizonylattetelListener`           | `onFlush`, `postFlush`    |
| `Kupon`                        | `KuponListener`                    | `prePersist`              |
| `Rendezveny`                   | `RendezvenyListener`               | `prePersist`              |
| `Jogareszvetel`                | `JogareszvetelListener`            | `onFlush`                 |
| `Partner`                      | `PartnerListener`                  | `onFlush`                 |
| `MPTNGYSzakmaianyag`           | `MPTNGYSzakmaianyagListener`       | `onFlush`                 |
| `Arsav`                        | `ArsavListener`                    | `onFlush`                 |

Source of truth: `bootstrap.php` lines 118–127. If you add a new listener, update both `bootstrap.php` and this table.

## Schema vs. data migrations

Two SQL workflows coexist — pick the right one:

- **Schema (DDL)** — annotations → DDL via `./updateschema.sh`. Use for new columns, new tables, type changes, indexes.
- **Data (DML)** — ad-hoc one-shot migrations in `runonce.php`. Use when existing rows need backfill, normalization, or seeded values. Each block is gated by a stored marker and runs once on the first admin request after deploy. Add a new gated block; do **not** edit a previously-shipped block.

If your entity change requires both (e.g. a new NOT NULL column on a populated table), do the data migration *first* — add the column nullable, backfill in `runonce.php`, then in a follow-up flip it to NOT NULL.

## After applying

- If `developer = 0` and a non-`apc` cache is configured (`file` adapter, `apc`, or `ArrayAdapter` per `config.ini`'s `cache` key), clear the configured Doctrine metadata cache. Stale metadata after schema changes presents as "column not found" or "unknown field" errors despite the DB being correct.
- If the changed entity is used in admin CRUD, check the matching `tpl/admin/{theme}/<entity>karbform.tpl` — a new editable field needs a form input there too. Remember each theme has its own templates.

## Quick reference

```bash
# Full safe cycle
grep '^main.theme' config.ini            # verify deployment
# ...edit Entities/<Name>.php...
./generateproxies.sh                     # refresh proxies
./updatesql.sh && cat update.sql         # review DDL
./updateschema.sh                        # apply (no rollback)
```
