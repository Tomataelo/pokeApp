<?php

namespace App\Exception;

class PokemonAlreadyExistsException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Pokemon already exists.', 409);
    }
}
