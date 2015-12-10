<?php

namespace G4\DataMapper\Common;

interface AdapterInterface
{

    public function delete($type, array $identifiers);

    public function insert($type, array $data);

    public function select();

    public function update();
}