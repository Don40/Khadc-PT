<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $table = 'registrations'; // 👈 Add this line

    protected $fillable = [
    'name', 'mobile', 'email', 'file_path',
    'monthly_salary', 'ten_percent'
];

}
