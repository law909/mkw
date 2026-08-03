<?php

namespace Traits;

/**
 * Bizonylat admin listanézetének URL-je, a bizonylatszámra előszűrve.
 *
 * A szűrőmezőket a mattable tölti fel a query stringből (jquery.mattable.js,
 * applyUrlToControls()), ezért elég az idfilter paraméter – a listasablonokban
 * és a getlistbody() metódusokban a mező mindenhol idfilter néven szerepel.
 *
 * A használó entitásnak getId()-vel kell rendelkeznie.
 */
trait HasListaUrl
{
    /**
     * @param string $routename a listanézet AltoRouter-neve
     *
     * @return string|null null, ha a bizonylatnak nincs száma, vagy a route ennél a
     *                     deploymentnél nincs regisztrálva – ilyenkor a hívó link
     *                     helyett sima szövegként mutassa a bizonylatszámot
     */
    protected function buildListaUrl($routename)
    {
        if (!$this->getId()) {
            return null;
        }
        try {
            return \mkw\store::getRouter()->generate(
                $routename,
                false,
                [],
                ['idfilter' => urlencode($this->getId())]
            );
        } catch (\Exception $e) {
            return null;
        }
    }
}
