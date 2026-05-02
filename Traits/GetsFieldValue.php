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
            $locale = \mkw\store::getMainLocale();
        }
        return $this->getFieldValue(\mkw\store::getLocalizedFieldName($fieldname, $locale));
    }
}