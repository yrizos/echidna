<?php

namespace Echidna;

use DataEntity\Entity as DataEntity;

class Entity extends DataEntity
{
    /**
     * @param string|\DataEntity\TypeInterface $type
     *
     * @return \DataEntity\TypeInterface
     */
    protected function getType($type)
    {
        return Echidna::type($type);
    }

    public function getFilteredValue($offset, $context = null)
    {
        $field = $this->getField($offset);
        $value = $this[$offset];

        return $field->filter($value, $context);
    }
} 