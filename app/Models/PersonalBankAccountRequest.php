<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PersonalBankAccountRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'personal_id',
        'bvn',
        'nin',
        'status',
        'admin_note',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            // 'bvn' => 'encrypted',
            // 'nin' => 'encrypted',
            'processed_at' => 'datetime',
        ];
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
}