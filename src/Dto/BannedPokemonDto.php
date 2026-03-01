<?php

namespace App\Dto;

class BannedPokemonDto
{
    public function __construct(
        public string $name {
            get {
                return $this->name;
            }
            set {
                $this->name = strtolower($value);
            }
        },
    ) {}
}
