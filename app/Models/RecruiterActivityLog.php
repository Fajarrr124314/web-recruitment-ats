<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruiterActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'application_id',
        'action',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
