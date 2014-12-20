<?php
namespace Echidna;

use DataEntity\EntityInterface;

interface ReferenceInterface extends EntityInterface
{

    public function __construct($type, $local_document, $local_field, $foreign_document, $foreign_field);

}