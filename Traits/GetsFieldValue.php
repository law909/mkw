<?php

namespace Traits;

trait GetsFieldValue
{
    public function getFieldValue($fieldName)
    {
        return $this->$fieldName;
    }
}