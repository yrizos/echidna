<?php

namespace Echidna;

trait DocumentBuilderTrait
{

    /** @var string */
    private $document;

    protected function setDocument($document)
    {
        if (!class_exists($document) || !in_array("Echidna\\DocumentInterface", class_implements($document))) throw new \InvalidArgumentException();

        $collection = $document::collection();

        if (empty($collection)) throw new \InvalidArgumentException();

        $this->document = $document;

        return $this;
    }

    public function getDocument()
    {

        return $this->document;
    }

    public function build(array $data = [])
    {
        return Echidna::buildDocument($this->getDocument(), $data);
    }

} 