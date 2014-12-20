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
} 