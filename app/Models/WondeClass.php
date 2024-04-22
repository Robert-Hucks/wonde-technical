<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Support\Collection;

/**
 * @property string $id
 * @property string $name
 * @property string|null $code
 *
 * @property-read Collection<Lesson> $lessons
 * @property-read Collection<Student> $students
 * @property-read Collection<Employee> $employees
 *
 * @mixin Eloquent
 */
class WondeClass extends Model
{
    protected $table = 'classes';

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'code',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    public $incrementing = false;

    public $timestamps = false;

    public function lessons(): Relations\HasMany
    {
        return $this->hasMany(Lesson::class, 'class_id');
    }

    public function students(): Relations\BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'class_students', 'class_id', 'student_id');
    }

    public function employees(): Relations\BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'class_employees', 'class_id');
    }
}
