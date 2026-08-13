<?php

namespace Services;

/**
 * A dokumentum fülek „Azonnali feltöltés” gombja mögötti feltöltés.
 *
 * A cél mappa a `config.ini` **path.dokumentum** értéke, a médiatár gyökeréhez
 * (`path.mediatar`, annak hiányában `path.ckfinder`) képest értelmezve – tehát
 * `path.dokumentum = dokumentum` mellett a fájl a `/kepek/dokumentum/`-ba kerül.
 *
 * Magát a fájlkezelést (kiterjesztés- és tartalomellenőrzés, névtisztítás,
 * ütközésfeloldás, méretkorlát) a MediatarService végzi, itt csak a mappa áll elő.
 * A típus szándékosan `Images`: annak a gyökere maga a médiatár gyökér, így a
 * konfigurált mappa bárhová tehető alá.
 */
class DokumentumUploadService
{

    const DEFAULTDIR = 'dokumentum';

    /** A cél mappa a médiatár gyökeréhez képest, `/…/` alakban. */
    public static function getPath(): string
    {
        $dir = trim(
            str_replace('\\', '/', (string)\mkw\store::getConfigValue('path.dokumentum', self::DEFAULTDIR)),
            '/'
        );
        return $dir === '' ? '/' : '/' . $dir . '/';
    }

    /** A cél mappa teljes URL-je, a felületen megjelenítendő magyarázathoz. */
    public static function getUrl(): string
    {
        return rtrim(MediatarService::getBaseUrl(), '/') . self::getPath();
    }

    /**
     * @param array $file egy $_FILES elem
     *
     * @return array a MediatarService::upload() eredménye (name, url, thumb)
     */
    public static function upload(array $file): array
    {
        $mediatar = new MediatarService('Images');
        $path = self::getPath();
        self::ensureFolder($mediatar, $path);
        return $mediatar->upload($file, $path);
    }

    /** Az első feltöltéskor a konfigurált mappa még nem létezik, szintenként létrehozzuk. */
    private static function ensureFolder(MediatarService $mediatar, string $path): void
    {
        $eddig = '/';
        foreach (array_filter(explode('/', trim($path, '/'))) as $szegmens) {
            try {
                $mediatar->absFolder($eddig . $szegmens . '/');
            } catch (\RuntimeException $e) {
                $mediatar->createFolder($eddig, $szegmens);
            }
            $eddig .= $szegmens . '/';
        }
    }

}
