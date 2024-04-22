<?php

namespace App\Data;

use App\Models\Lesson;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

class LessonData extends Data
{
    public function __construct(
        public readonly string $id,
        #[WithTransformer(DateTimeInterfaceTransformer::class)]
        public readonly CarbonImmutable $start_at,
        #[WithTransformer(DateTimeInterfaceTransformer::class)]
        public readonly CarbonImmutable $end_at,
        public readonly ClassData $class,
        /** @var array<StudentData::class> */
        public readonly array $students,
        /** @var array<EmployeeData::class> */
        public readonly array $employees,
    ) {}

    public static function fromModel(Lesson $lesson): static
    {
        return new static(
            $lesson->id,
            $lesson->start_at,
            $lesson->end_at,
            ClassData::from($lesson->wondeClass),
            StudentData::collect($lesson->wondeClass->students)->toArray(),
            EmployeeData::collect($lesson->wondeClass->employees)->toArray(),
        );
    }
}
