<?php

// RoleDetail Model
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleDetail extends Model
{
    use HasFactory;

    protected $fillable = ['staff_id', 'role_name', 'description'];

    // Quan hệ với Staff
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }
}

