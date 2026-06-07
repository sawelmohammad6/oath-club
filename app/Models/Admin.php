<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = ['name', 'email', 'password', 'is_master'];
    protected $hidden = ['password', 'remember_token'];
}
