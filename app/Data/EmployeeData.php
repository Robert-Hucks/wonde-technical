<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class EmployeeData extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly string|null $title,
        public readonly string|null $forename,
        public readonly string|null $surname,
    ) {}
}
