<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $table = 'applications';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'location',
        'cv',
        'job_id',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
