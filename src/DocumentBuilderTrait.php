<?php

namespace Echidna;

trait DocumentBuilderTrait
{

    /** @var string */
    private $document;

    /**
     * @param string|DocumentInterface $document
     * @return $this
     * @throws \InvalidArgumentException
     */
    protected function setDocument($document)
    {
        if (!class_exists($document) || !in_array("Echidna\\DocumentInterface", class_implements($document))) throw new \InvalidArgumentException();

        $collection = $document::collection();

        if (empty($collection)) throw new \InvalidArgumentException();

        $this->document = $document;

        return $this;
    }

    /**
     * @return string
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param array $data
     * @param bool $isNew
     * @return
     */
    public function build(array $data = [], $isNew = true)
    {
        return self::buildDocument($this->getDocument(), $data, $isNew);
    }

    /**
     * @param $document
     * @param array $data
     * @param bool $isNew
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public static function buildDocument($document, array $data = [], $isNew = true)
    {
        if (!($document instanceof DocumentInterface)) {
            if (!class_exists($document) || !in_array("Echidna\\DocumentInterface", class_implements($document))) throw new \InvalidArgumentException();

            $document = new $document;
        }

        $document->setData($data)->setNew($isNew);

        return $document;
    }

} 