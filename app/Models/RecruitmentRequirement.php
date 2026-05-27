<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruitmentRequirement extends Model
{
    protected $fillable = [
        'type',
        'question',
        'options',
        'is_required',
        'is_active',
        'order',
    ];
}
