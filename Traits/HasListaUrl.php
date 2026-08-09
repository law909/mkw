<?php

namespace Traits;

/**
 * Bizonylat admin URL-jei: a bizonylatszámra előszűrt listanézet és a karbantartó.
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
        return $this->buildAdminUrl($routename, ['idfilter' => $this->getId()]);
    }

    /**
     * A bizonylat karbantartójának (viewkarb) URL-je. A paraméterezés a mattable
     * szerkesztő linkjével azonos (jquery.mattable.js, doEditLink()).
     *
     * @param string $routename a karbantartó AltoRouter-neve
     *
     * @return string|null ugyanaz a null-szabály, mint a buildListaUrl()-nél
     */
    protected function buildKarbUrl($routename)
    {
        return $this->buildAdminUrl($routename, ['id' => $this->getId(), 'oper' => 'edit']);
    }

    private function buildAdminUrl($routename, array $query)
    {
        if (!$this->getId()) {
            return null;
        }
        try {
            return \mkw\store::getRouter()->generate(
                $routename,
                false,
                [],
                array_map('urlencode', $query)
            );
        } catch (\Exception $e) {
            return null;
        }
    }
}
