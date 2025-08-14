<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // <- import

class Employee extends Model
{
    use HasFactory, SoftDeletes; // <- add SoftDeletes

    protected $fillable = [
        'name', 'email', 'phone', 'position', 'salary', 'photo_path'
    ];

    protected $dates = ['deleted_at']; // optional, SoftDeletes automatically manages it
}
