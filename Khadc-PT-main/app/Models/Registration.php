<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

        class Registration extends Model
        {
            use HasFactory;

            protected $table = 'registrations'; 

            protected $fillable = [
            'name', 'mobile', 'email', 'file_path',
            'monthly_salary', 'ten_percent', 'user_id'
        ];
        public function user() {
                return $this->belongsTo(User::class, 'user_id');
            }
        
        }
