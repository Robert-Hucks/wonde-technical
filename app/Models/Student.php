<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Support\Collection;

/**
 * @property string $id
 * @property string|null $forename
 * @property string $surname
 *
 * @property-read Collection<WondeClass> $classes
 *
 * @mixin Eloquent
 */
class Student extends Model
{
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'forename',
        'surname',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    public $incrementing = false;

    public $timestamps = false;

    public function classes(): Relations\BelongsToMany
    {
        return $this->belongsToMany(WondeClass::class, 'class_students');
    }
}
