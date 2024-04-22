<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Support\Collection;

/**
 * @property string $id
 * @property string|null $title
 * @property string|null $forename
 * @property string|null $surname
 * @property bool $current
 *
 * @property-read Collection<WondeClass> $classes
 * @property-read Collection<Lesson> $lessons
 *
 * @mixin Eloquent
 */
class Employee extends Model
{
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'title',
        'forename',
        'surname',
        'current',
    ];

    protected $casts = [
        'id' => 'string',
        'current' => 'boolean',
    ];

    public $incrementing = false;

    public $timestamps = false;

    public function classes(): Relations\BelongsToMany
    {
        return $this->belongsToMany(WondeClass::class, 'class_employees', 'employee_id', 'class_id');
    }

    public function lessons(): Relations\HasMany
    {
        return $this->hasMany(Lesson::class, 'employee_id');
    }
}
