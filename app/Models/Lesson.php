<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;

/**
 * @property string $id
 * @property string|null $class_id
 * @property string|null $employee_id
 * @property string|null $room
 * @property CarbonImmutable $start_at
 * @property CarbonImmutable $end_at
 *
 * @property-read WondeClass $wondeClass
 * @property-read Employee $employee
 *
 * @mixin Eloquent
 */
class Lesson extends Model
{
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'class_id',
        'employee_id',
        'room',
        'start_at',
        'end_at',
    ];

    protected $casts = [
        'id' => 'string',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public $incrementing = false;

    public $timestamps = false;

    public function wondeClass(): Relations\BelongsTo
    {
        return $this->belongsTo(WondeClass::class, 'class_id');
    }

    public function employee(): Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
