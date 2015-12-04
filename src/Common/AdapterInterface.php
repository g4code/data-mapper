<?php

namespace G4\DataMapper\Common;

interface AdapterInterface
{


    public function connect();

    public function delete();

    public function insert(array $data);

    public function select();

    public function update();
}