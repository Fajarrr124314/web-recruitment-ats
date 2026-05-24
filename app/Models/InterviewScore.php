<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'interviewer_id',
        'rating',
        'notes',
    ];

    /**
     * Get the application being scored.
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Get the user (HRD interviewer) who submitted the score.
     */
    public function interviewer()
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }
}
