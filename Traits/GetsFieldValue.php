<?php

namespace Traits;

trait GetsFieldValue
{
    public function getFieldValue($fieldName)
    {
        return $this->$fieldName;
    }

    public function getLocalizedFieldValue($fieldname, $locale = null)
    {
        if (!$locale) {
            $locale = \mkw\store::getWebshopLongLocale();
        }
        return $this->getFieldValue(\mkw\store::getLocalizedFieldName($fieldname, $locale));
    }

    /**
     * Fordított érték, üres fordítás esetén az alapnyelvű. Nevekre és címekre való: ott a
     * kitöltetlen fordítás üres title-t és üres h1-et hagy. Törzsszövegre nem használjuk,
     * mert ott a szándékosan üresen hagyott fordítást is felülírná.
     */
    public function getLocalizedFieldValueOrDefault($fieldname, $locale = null)
    {
        $ertek = $this->getLocalizedFieldValue($fieldname, $locale);
        return ($ertek === null || $ertek === '') ? $this->getFieldValue($fieldname) : $ertek;
    }
}