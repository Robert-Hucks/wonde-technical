<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class StudentData extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly string|null $forename,
        public readonly string $surname,
    ) {}
}
