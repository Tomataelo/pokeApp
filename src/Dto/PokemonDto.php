<?php

namespace App\Dto;

class PokemonDto
{
    public function __construct(
        public string $name {
            get {
                return $this->name;
            }
            set {
                $this->name = $value;
            }
        },
        public int $weight {
            get {
                return $this->weight;
            }
            set {
                $this->weight = $value;
            }
        },
        public int $height {
            get {
                return $this->height;
            }
            set {
                $this->height = $value;
            }
        },
    ){}
}
