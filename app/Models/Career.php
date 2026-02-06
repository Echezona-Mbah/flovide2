<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
      protected $fillable = [
        'title',
        'job_type',
        'location',
        'description',
        'requirements',
        'status',
    ];
}
