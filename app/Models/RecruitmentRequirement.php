<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruitmentRequirement extends Model
{
    protected $fillable = [
        'type',
        'question',
        'is_required',
        'is_active',
        'order',
    ];
}
