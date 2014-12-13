<?php

namespace Echidna;

class EntityBuilder {

    public static function build($entity, array $data = [])
    {
        if (!($entity instanceof EntityInterface)) {
            if (!class_exists($entity) || !in_array("Echidna\\EntityInterface", class_implements($entity))) throw new \InvalidArgumentException();

            $entity = new $entity;
        }

        $entity->setData($data);

        return $entity;
    }

} 