<?php

namespace Echidna;

interface EntityBuilderInterface
{

    public function getEntity();

    public function build(array $data = []);

} 