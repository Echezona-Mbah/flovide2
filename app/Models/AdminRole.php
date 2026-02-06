<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class AdminRole extends Model
{
   use HasFactory;

    // Optional: if your table name doesn't follow Laravel's convention
    protected $table = 'admin_roles';

    // Fields that can be mass assigned
    protected $fillable = [
        'name',
        'description'
    ];
}
