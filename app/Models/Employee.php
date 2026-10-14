<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    //
    use SoftDeletes;
    
    protected $fillable = [
        'public_id',
        'employee_id',
        'name',
        'job_title',
        'department',
        'work_email',
        'employment_date',
        'id_card_expiration_date',
        'status',
        'photo_path',
    ];
    protected function casts(): array
    {
        return [
            'employment_date' => 'date',
            'id_card_expiration_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Employee $employee) {
            if (empty($employee->public_id)) {
                $employee->public_id = (string) Str::uuid();
            }
        });
    }
}
