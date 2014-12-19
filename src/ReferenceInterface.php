<?php
namespace Echidna;

interface ReferenceInterface
{

    public function __construct($name, $type, $local_collection, $local_field, $foreign_collection, $foreign_field);

    public function getName();

    public function getType();

    public function getLocalCollection();

    public function getLocalField();

    public function getForeignCollection();

    public function getForeignCollectionField();

} 