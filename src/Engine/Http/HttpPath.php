<?php

namespace G4\DataMapper\Engine\Http;

use G4\DataMapper\Common\CollectionNameInterface;
use G4\DataMapper\Exception\HttpPathException;

class HttpPath implements CollectionNameInterface
{

    const SLASH = '/';

    /**
     * @var string
     */
    private $path;


    /**
     * HttpPath constructor.
     * @param array ...$parts string
     */
    public function __construct(... $parts)
    {
        $path = join(self::SLASH, $parts);

        if (!is_string($path) || strlen($path) === 0) {
            throw new HttpPathException();
        }

        $this->path = $path;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->path;
    }
}
