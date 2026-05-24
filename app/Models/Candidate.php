<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'skills',
        'work_history',
        'cv_path',
        'portfolio_path',
    ];

    protected $casts = [
        'skills' => 'array',
    ];

    /**
     * Get the user that owns the candidate profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the applications submitted by this candidate.
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
