<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'job_title',
        'company_name',
        'status',
        'rejection_reason',
        'rejected_at',
    ];

    protected $casts = [
        'rejected_at' => 'datetime',
    ];

    /**
     * Get the candidate profile associated with the application.
     */
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Get the interview scores for the application.
     */
    public function interviewScores()
    {
        return $this->hasMany(InterviewScore::class);
    }

    public function answers()
    {
        return $this->hasMany(ApplicationAnswer::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(RecruiterActivityLog::class)->latest();
    }
}
