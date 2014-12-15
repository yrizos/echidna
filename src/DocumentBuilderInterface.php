<?php

namespace Echidna;

interface DocumentBuilderInterface
{

    public function getDocument();

    public function build(array $data = [], $isNew = true);

} 